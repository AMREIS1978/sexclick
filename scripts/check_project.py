from pathlib import Path
root=Path(__file__).resolve().parents[1]
required=['README.md','AGENTS.md','docs/COMPATIBILIDADE_DREAMLOVE.md','wp-content/themes/sexclick/style.css','wp-content/themes/sexclick/functions.php','wp-content/themes/sexclick/header.php','wp-content/themes/sexclick/footer.php','wp-content/themes/sexclick/front-page.php','wp-content/themes/sexclick/index.php','wp-content/themes/sexclick/woocommerce.php','wp-content/themes/sexclick/assets/shop.css','wp-content/themes/sexclick/assets/shop.js','wp-content/plugins/sexclick-operations/sexclick-operations.php']
for name in required:
 assert (root/name).is_file(),name
 assert (root/name).stat().st_size,name
for path in (root/'wp-content').rglob('*'):
 if path.is_file():
  data=path.read_text()
  assert 'localStorage' not in data,str(path)
  assert 'api.dreamlove' not in data,str(path)
print('PASS: project structure and absence of demo cart / invented API host.')
