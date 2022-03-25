var href = tasScripts.template_url+'/images/loader.gif'
console.log(href);
let html = '<!DOCTYPE HTML> <html> <head> <title>Title of the document</title> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> </head><body style="background: #fff"><div style="position: absolute;top: 50%;left: 50%;-moz-transform: translateX(-50%) translateY(-50%);-webkit-transform: translateX(-50%) translateY(-50%);transform: translateX(-50%) translateY(-50%);"><img src="'+href+'"/></div></body></html>'
wp.hooks.addFilter( 
  'editor.PostPreview.interstitialMarkup', 
  'my-plugin/custom-preview-message', 
  function() { 
    return html; 
  } 
);

//Copiar titulo del anexo
function copiarTextoAnexo() {
  /* Get the text field */
  var copyText = document.getElementById("texto_anexo");
  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /*For mobile devices*/
  /* Copy the text inside the text field */
  document.execCommand("copy");
  /* Alert the copied text */
  alert("Listo: " + copyText.value);
}