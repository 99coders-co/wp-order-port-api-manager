
let filter_val = document.getElementById('filter_val');
filter_val.addEventListener('change',function(e){
let dynamic_shortcode = document.getElementById("dynamic_shortcode");
let shortcode = `[orderport-products type="${e.target.value}"]`
dynamic_shortcode.innerHTML = shortcode
})
