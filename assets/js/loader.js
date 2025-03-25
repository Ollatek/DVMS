function showLoader(){
document.getElementById('loader;). style.display='block';
}

function hideLoader(){
document.getElementById('loader;). style.display='none';
}

document.addEventListener('DOMContentLoaded'.function () {
	showLoader();
setTimeout(function(){
	hideLoader();
}, 1000);
});