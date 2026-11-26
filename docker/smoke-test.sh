#!/bin/sh
set -eu

name="pip-topsis-smoke-$$"
app="$name-app"
db="$name-db"

cleanup() {
    if [ "$?" -ne 0 ]; then
        docker logs "$app" 2>/dev/null || true
        docker logs "$db" 2>/dev/null || true
    fi
    docker rm -fv "$app" "$db" >/dev/null 2>&1 || true
    docker network rm "$name" >/dev/null 2>&1 || true
}
trap cleanup EXIT

wait_for() {
    attempts=0
    until "$@" >/dev/null 2>&1; do
        attempts=$((attempts + 1))
        [ "$attempts" -lt 60 ] || return 1
        sleep 1
    done
}

docker network create "$name" >/dev/null
docker run -d --name "$db" --network "$name" \
    --tmpfs /var/lib/mysql \
    -e MYSQL_ROOT_PASSWORD=smoke-only -e MYSQL_DATABASE=pip_topsis \
    -e MYSQL_USER=pip_topsis -e MYSQL_PASSWORD=smoke-only \
    mysql:8.4 >/dev/null
wait_for docker exec "$db" mysqladmin ping -h 127.0.0.1 --silent

key=$(docker run --rm --entrypoint php pip-topsis:coolify artisan key:generate --show)
docker run -d --name "$app" --network "$name" \
    --tmpfs /var/www/html/storage \
    -e APP_KEY="$key" -e APP_URL=http://localhost \
    -e DB_CONNECTION=mysql -e DB_HOST="$db" -e DB_DATABASE=pip_topsis \
    -e DB_USERNAME=pip_topsis -e DB_PASSWORD=smoke-only \
    -e SESSION_DRIVER=database -e CACHE_STORE=database -e QUEUE_CONNECTION=sync \
    pip-topsis:coolify >/dev/null
wait_for docker exec "$app" curl --fail --silent http://127.0.0.1/up
docker exec --user www-data "$app" php artisan migrate --force
status=$(docker exec "$app" curl --silent --output /dev/null --write-out '%{http_code}' http://127.0.0.1/login)
[ "$status" = 200 ]
docker exec --user www-data "$app" sh -ec '
    test ! -f .env
    test ! -f public/hot
    test -s public/build/manifest.json
    test -w storage/app/private
    test -w storage/app/public
    test -w storage/fonts
    test -w bootstrap/cache
'
docker exec --user www-data "$app" php -r '
    require "vendor/autoload.php";
    $app = require "bootstrap/app.php";
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView("laporan.pdf", [
        "hasil" => collect(), "filter" => "semua", "kelas" => null, "periode" => null
    ])->output();
    if (!str_starts_with($pdf, "%PDF-")) exit(1);
    $xlsx = Maatwebsite\Excel\Facades\Excel::raw(
        new App\Exports\SiswaTemplateExport(), Maatwebsite\Excel\Excel::XLSX
    );
    if (!str_starts_with($xlsx, "PK")) exit(1);
'
printf 'Docker smoke test passed: MySQL migrations, HTTP health/login, assets, storage, PDF, and Excel.\n'
