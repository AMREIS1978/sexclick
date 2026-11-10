"""One native WordPress plugin ZIP. Deliberately not a migration backup."""
from pathlib import Path
from zipfile import ZipFile, ZIP_DEFLATED
import hashlib, json, re, subprocess, sys

root=Path(__file__).resolve().parents[1]
subprocess.run([sys.executable,str(root/'scripts/package.py')],check=True)
plugin=root/'wp-content/plugins/sexclick-hostinger'
payload=plugin/'payload';payload.mkdir(exist_ok=True)
woo=root/'vendor-cache/woocommerce.zip'
if not woo.is_file(): raise SystemExit('Falta vendor-cache/woocommerce.zip, obtido do diretório oficial WordPress.org.')
with ZipFile(woo) as archive:
 assert archive.testzip() is None,'WooCommerce ZIP corrompido'
 header=archive.read('woocommerce/woocommerce.php').decode()
 for info in archive.infolist():
  parts=Path(info.filename).parts
  assert not Path(info.filename).is_absolute() and '..' not in parts and parts[0]=='woocommerce'

def field(text,name,default=''):
 match=re.search(r'^\s*\*?\s*'+re.escape(name)+r':\s*([^\r\n]+)',text,re.M)
 return match.group(1).strip() if match else default

manifest={}
extracted_bytes=0
for name,source,version in [
 ('woocommerce',woo,field(header,'Version')),
 ('theme',root/'dist/sexclick-theme.zip',field((root/'wp-content/themes/sexclick/style.css').read_text(),'Version')),
 ('operations',root/'dist/sexclick-operations.zip',field((root/'wp-content/plugins/sexclick-operations/sexclick-operations.php').read_text(),'Version')),
]:
 data=source.read_bytes();(payload/(name+'.zip')).write_bytes(data)
 manifest[name]={'version':version,'sha256':hashlib.sha256(data).hexdigest()}
 with ZipFile(source) as archive:extracted_bytes+=sum(item.file_size for item in archive.infolist())
manifest['woocommerce']['requires_wp']=field(header,'Requires at least','6.5')
manifest['minimum_free_bytes']=2*extracted_bytes+woo.stat().st_size
(payload/'manifest.json').write_text(json.dumps(manifest,indent=2))
(payload/'index.php').write_text('<?php http_response_code(403); exit;')
(payload/'.htaccess').write_text('Require all denied\n')
out=root/'dist/SEXCLICK-INSTALAR-NO-WORDPRESS-2.0.zip'
with ZipFile(out,'w',ZIP_DEFLATED,compresslevel=9) as archive:
 for file in sorted(plugin.rglob('*')):
  if file.is_file():archive.write(file,Path('sexclick-hostinger')/file.relative_to(plugin))
with ZipFile(out) as archive:
 assert archive.testzip() is None
 assert 'sexclick-hostinger/sexclick-hostinger.php' in archive.namelist()
 assert 'wp-config.php' not in archive.namelist()
print(f'{out.name}: {out.stat().st_size:,} bytes; plugin único, três pacotes locais verificados.')
