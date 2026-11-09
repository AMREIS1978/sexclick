#!/usr/bin/env bash
set -euo pipefail
if [[ $# -ne 1 ]]; then
 echo 'Utilização: bash scripts/setup-staging.sh /caminho/do/wordpress-staging' >&2
 exit 1
fi
project_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
wp_dir="$(cd "$1" && pwd)"
command -v wp >/dev/null || { echo 'WP-CLI é necessário.' >&2; exit 1; }
wp --path="$wp_dir" core is-installed
site_mode="$(wp --path="$wp_dir" eval 'echo wp_get_environment_type();')"
[[ "$site_mode" = staging || "$site_mode" = local || "$site_mode" = development ]] || { echo 'Recusado: a instalação não está identificada como staging/local/development.' >&2; exit 1; }
[[ -d "$wp_dir/wp-content" ]] || exit 1
# Refuse an in-place copy over the source directory itself.
[[ "$project_dir" != "$wp_dir" ]] || { echo 'Use uma instalação WordPress separada.' >&2; exit 1; }
if wp --path="$wp_dir" eval 'exit(get_option("scops_sales_enabled",false)?1:0);'; then :; else
 echo 'Recusado: a loja já tem vendas ativadas.' >&2; exit 1
fi
# Preserve an existing staging version before updating these two project directories.
stamp="$(date -u +%Y%m%dT%H%M%SZ)"
for item in themes/sexclick plugins/sexclick-operations; do
 if [[ -e "$wp_dir/wp-content/$item" ]]; then
  backup_dir="$project_dir/.staging-backups/$stamp/$item"
  mkdir -p "$backup_dir"
  cp -a "$wp_dir/wp-content/$item/." "$backup_dir/"
 fi
done
python3 "$project_dir/scripts/compile-translations.py"
mkdir -p "$wp_dir/wp-content/themes/sexclick" "$wp_dir/wp-content/plugins/sexclick-operations"
cp -a "$project_dir/wp-content/themes/sexclick/." "$wp_dir/wp-content/themes/sexclick/"
cp -a "$project_dir/wp-content/plugins/sexclick-operations/." "$wp_dir/wp-content/plugins/sexclick-operations/"
if ! wp --path="$wp_dir" plugin is-installed woocommerce; then
 wp --path="$wp_dir" plugin install woocommerce
fi
wp --path="$wp_dir" plugin activate woocommerce
wp --path="$wp_dir" plugin activate sexclick-operations
wp --path="$wp_dir" theme activate sexclick
wp --path="$wp_dir" language core install pt_PT --activate
wp --path="$wp_dir" language plugin install woocommerce pt_PT
wp --path="$wp_dir" eval-file "$project_dir/scripts/setup-staging.php"
