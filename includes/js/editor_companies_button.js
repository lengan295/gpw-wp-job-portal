(function () {
    tinymce.PluginManager.add('wpjobportal_companies_button', function (editor, url) {
        editor.addButton('wpjobportal_companies_button', {
            title: jslang.main_comp_title,
            text: jslang.main_comp_text,
            icon: 'icon wpjobportal-ouricon-img',
            onclick: function () {
                editor.windowManager.open({
                    title: jslang.window_comp_title,
                    width: 500,
                    height: 400,
                    autoScroll: true,
                    classes: 'wpjobportaljobseditorclass',
                    body: [
                        {type: 'textbox', name: 'title', label: jslang.title},
                        {type: 'listbox', name: 'companytype', label: jslang.typeofcompany,
                            'values': [
                                {text: 'Select type', value: '0'},
                                {text: 'Featured companies', value: '2'}
                            ]
                        },
                        {type: 'listbox', name: 'showtitle', label: jslang.showtitle,
                            'values': [
                                {text: 'Show', value: '1'},
                                {text: 'Hide', value: '0'}
                            ]
                        },
                        {type: 'listbox', name: 'companylogo', label: jslang.companylogo,
                            'values': [
                                {text: 'Show all', value: '1'},
                                {text: 'Hide all', value: '0'},
                                {text: 'Show desktop and tablet', value: '2'},
                                {text: 'Show desktop and mobile', value: '3'},
                                {text: 'Show tablet and mobile', value: '4'},
                                {text: 'Show desktop', value: '5'},
                                {text: 'Show tablet', value: '6'},
                                {text: 'Show mobile', value: '7'}
                            ]
                        },
                        {type: 'listbox', name: 'category', label: jslang.category,
                            'values': [
                                {text: 'Show all', value: '1'},
                                {text: 'Hide all', value: '0'},
                                {text: 'Show desktop and tablet', value: '2'},
                                {text: 'Show desktop and mobile', value: '3'},
                                {text: 'Show tablet and mobile', value: '4'},
                                {text: 'Show desktop', value: '5'},
                                {text: 'Show tablet', value: '6'},
                                {text: 'Show mobile', value: '7'}
                            ]
                        },
                        {type: 'listbox', name: 'location', label: jslang.location,
                            'values': [
                                {text: 'Show all', value: '1'},
                                {text: 'Hide all', value: '0'},
                                {text: 'Show desktop and tablet', value: '2'},
                                {text: 'Show desktop and mobile', value: '3'},
                                {text: 'Show tablet and mobile', value: '4'},
                                {text: 'Show desktop', value: '5'},
                                {text: 'Show tablet', value: '6'},
                                {text: 'Show mobile', value: '7'}
                            ]
                        },
                        {type: 'listbox', name: 'posted', label: jslang.posted,
                            'values': [
                                {text: 'Show all', value: '1'},
                                {text: 'Hide all', value: '0'},
                                {text: 'Show desktop and tablet', value: '2'},
                                {text: 'Show desktop and mobile', value: '3'},
                                {text: 'Show tablet and mobile', value: '4'},
                                {text: 'Show desktop', value: '5'},
                                {text: 'Show tablet', value: '6'},
                                {text: 'Show mobile', value: '7'}
                            ]
                        },
                        {type: 'textbox', name: 'noofcompanies', label: jslang.noofcompanies},
                        {type: 'listbox', name: 'listingstyle', label: jslang.listingstyle,
                            'values': [
                                {text: 'Box style', value: '1'},
                                {text: 'List style', value: '0'}
                            ]
                        },
                        {type: 'listbox', name: 'boxstyle', label: jslang.boxstyle,
                            'values': [
                                {text: 'Horizontal', value: '1'},
                                {text: 'Verticle', value: '2'}
                            ]
                        },
                        {type: 'listbox', name: 'fieldcolumn', label: jslang.fieldcolumn,
                            'values': [
                                {text: '1', value: '1'},
                                {text: '2', value: '2'},
                                {text: '3', value: '3'},
                                {text: '4', value: '4'},
                                {text: '5', value: '5'}
                            ]
                        },
                        {type: 'textbox', name: 'moduleheight', label: jslang.moduleheight},
                        {type: 'textbox', name: 'companyheight', label: jslang.companyheight},
                        {type: 'textbox', name: 'complogowidth', label: jslang.logowidth},
                        {type: 'textbox', name: 'complogoheight', label: jslang.logoheight},
                        {type: 'listbox', name: 'nofcompanies', label: jslang.noofcompanies,
                            'values': [
                                {text: '1', value: '1'},
                                {text: '2', value: '2'},
                                {text: '3', value: '3'},
                                {text: '4', value: '4'},
                                {text: '5', value: '5'}
                            ]
                        },
                        {type: 'listbox', name: 'nofcompaniesrowtab', label: jslang.noofcompaniestablet,
                            'values': [
                                {text: '1', value: '1'},
                                {text: '2', value: '2'},
                                {text: '3', value: '3'},
                                {text: '4', value: '4'},
                                {text: '5', value: '5'}
                            ]
                        },
                        {type: 'textbox', name: 'topmargin', label: jslang.topmargin},
                        {type: 'textbox', name: 'leftmargin', label: jslang.leftmargin},
                        {type: 'textbox', name: 'titlecolor', label: jslang.titlecolor},
                        {type: 'textbox', name: 'titleborderbottom', label: jslang.titleborderbottom},
                        {type: 'textbox', name: 'backgroundcolor', label: jslang.backgroundcolor},
                        {type: 'textbox', name: 'bordercolor', label: jslang.bordercolor},
                        {type: 'textbox', name: 'datalabelcolor', label: jslang.datalabelcolor},
                        {type: 'textbox', name: 'datavaluecolor', label: jslang.datavaluecolor}
                    ],
                    onsubmit: function (e) {
                        var shcode = makeShortcoldecomp(e.data);
                        editor.insertContent(shcode);
                    }
                });
            }
        });
    });
})();

function makeShortcoldecomp(data) {
    var c = '[wpjobportal_companies';
    if (data.title != '')
        c += ' title="' + data.title + '"';
    c += ' companytype="' + data.companytype + '"';
    c += ' showtitle="' + data.showtitle + '"';
    c += ' companylogo="' + data.companylogo + '"';
    c += ' category="' + data.category + '"';
    c += ' location="' + data.location + '"';
    c += ' posted="' + data.posted + '"';
    if (data.noofcompanies != '')
        c += ' noofcompanies="' + data.noofcompanies + '"';
    c += ' listingstyle="' + data.listingstyle + '"';
    c += ' boxstyle="' + data.boxstyle + '"';
    c += ' fieldcolumn="' + data.fieldcolumn + '"';
    if (data.moduleheight != 0)
        c += ' moduleheight="' + data.moduleheight + '"';
    if (data.companyheight != 0)
        c += ' companyheight="' + data.companyheight + '"';
    if (data.complogowidth != 0)
        c += ' complogowidth="' + data.complogowidth + '"';
    if (data.complogoheight != 0)
        c += ' complogoheight="' + data.complogoheight + '"';
    c += ' nofcompanies="' + data.nofcompanies + '"';
    c += ' nofcompaniesrowtab="' + data.nofcompaniesrowtab + '"';
    if (data.topmargin != '')
        c += ' topmargin="' + data.topmargin + '"';
    if (data.leftmargin != '')
        c += ' leftmargin="' + data.leftmargin + '"';
    if (data.titlecolor != '')
        c += ' titlecolor="' + data.titlecolor + '"';
    if (data.bordercolor != '')
        c += ' bordercolor="' + data.bordercolor + '"';
    if (data.titleborderbottom != '')
        c += ' titleborderbottom="' + data.titleborderbottom + '"';
    if (data.backgroundcolor != '')
        c += ' backgroundcolor="' + data.backgroundcolor + '"';
    if (data.datavaluecolor != '')
        c += ' datavaluecolor="' + data.datavaluecolor + '"';
    if (data.datalabelcolor != '')
        c += ' datalabelcolor="' + data.datalabelcolor + '"';
    c += ']';
    return c;
}
