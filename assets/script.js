console.log("JavaScript Loaded Successfully");

document.addEventListener('click', function(e){
	const t = e.target;
	if (t.matches('[data-confirm]')){
		const msg = t.getAttribute('data-confirm') || 'Are you sure?';
		if (!confirm(msg)) e.preventDefault();
	}
});

// simple keyboard: focus search input by pressing "/"
document.addEventListener('keydown', function(e){
	if (e.key === '/' && !e.metaKey && !e.ctrlKey && !e.altKey){
		const s = document.querySelector('input[type="search"]');
		if (s){ e.preventDefault(); s.focus(); }
	}
});
