const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');
const fs = require('node:fs');
const assert = require('node:assert/strict');
(async () => {
 const browser = await chromium.launch();
 const page = await browser.newPage();
 const errors=[];page.on('pageerror',e=>errors.push(e.message));
 const base=(process.env.SEXCLICK_TEST_URL || 'http://127.0.0.1:8080').replace(/\/$/,'');
 fs.mkdirSync('test-results',{recursive:true});
 for(const width of [320,375,390,430,1280]){
  await page.setViewportSize({width,height:900});
  const response=await page.goto(base);assert.equal(response.status(),200);
  assert.equal(await page.locator('html').getAttribute('lang'),'pt-PT');
  assert.equal(await page.locator('body').evaluate(()=>document.documentElement.scrollWidth>innerWidth),false,`Overflow at ${width}`);
  const trigger=width<801?page.locator('.sc-mobile-nav [data-search-open]'):page.locator('.sc-tools [data-search-open]');
  await trigger.click();await page.locator('#sc-search-dialog').waitFor({state:'visible'});
  await page.locator('#sc-search-dialog input[type=search]').fill('zzzz-sem-resultados');
  await page.locator('#sc-search-dialog button[type=submit]').click();
  assert.equal(await page.locator('li.product').count(),0);
  assert.match(await page.locator('main').innerText(),/produtos|resultados/i);
  await page.goto(base);await trigger.click();await page.keyboard.press('Escape');
  assert.equal(await page.locator('#sc-search-dialog').isVisible(),false);
  await page.screenshot({path:`test-results/mobile-${width}.png`,fullPage:true});
 }
 await page.goto(base+'/?s=Produto+CI&post_type=product');await page.getByRole('heading',{name:'Produto CI',exact:true}).waitFor();
 await page.locator('.woocommerce-loop-product__link').first().click();
 assert.equal(await page.locator('.single_add_to_cart_button').count(),0,'No purchase button before launch');
 assert.deepEqual(errors,[]);
 await browser.close();console.log('PASS: responsive widths, real search, modal, product and launch protection.');
})().catch(e=>{console.error(e);process.exit(1)});
