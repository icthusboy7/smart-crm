$(document).ready(function () {
    const isSpecialSelector = "input[id*='_isSpecial']";

    const name = $(isSpecialSelector).attr("name");
    console.log('name: ' + name);

    $(isSpecialSelector).change(function () {
        $("div[id*='_form']").toggle(this.checked);
    }).change();
});
