function toggleTheme() {
	// Obtains an array of all <link>
	// elements.
	// Select your element using indexing.
	var theme = document.getElementsByTagName('link')[0];

	// Change the value of href attribute 
	// to change the css sheet.
	if (theme.getAttribute('href') == 'styles/light.css') 
	{
		theme.setAttribute('href', 'styles/dark.css');
	} 
	else 
	{
		theme.setAttribute('href', 'styles/light.css');
	}
}