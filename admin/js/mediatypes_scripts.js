const choices = document.querySelectorAll('label.mediatypes_btn input');
choices.forEach(item =>
{
	item.addEventListener('click', (e) =>
	{
		e.target.closest('.mediatypes_btn').classList.toggle('active');
	});
})