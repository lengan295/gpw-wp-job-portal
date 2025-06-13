(function () {
    tinymce.PluginManager.add('wpjobportal_jobs_button', function (editor, url) {
        editor.addButton('wpjobportal_jobs_button', {
            title: jslang.main_job_title,
            text: jslang.main_job_text,
            icon: 'icon wpjobportal-ouricon-img',
            onclick: function () {
                editor.windowManager.open({
                    title: jslang.window_job_title,
                    width: 500,
                    height: 400,
                    autoScroll: true,
                    classes: 'wpjobportaljobseditorclass',
                    body: [
                        {type: 'textbox', name: 'title', label: jslang.title},
                        {type: 'listbox', name: 'typeofjobs', label: jslang.typeofjob,
                            'values': [
                                {text: 'Select type', value: '0'},
                                {text: 'Newest jobs', value: '1'},
                                {text: 'Top jobs', value: '2'},
                                {text: 'Hot jobs', value: '3'},
                                {text: 'Featured jobs', value: '5'}
                            ]
                        },
                        {type: 'listbox', name: 'showtitle', label: jslang.showtitle,
                            'values': [
                                {text: 'Show', value: '1'},
                                {text: 'Hide', value: '0'}
                            ]
                        },
                        {type: 'listbox', name: 'company', label: jslang.company,
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
                        {type: 'listbox', name: 'jobtype', label: jslang.jobtype,
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
                        {type: 'textbox', name: 'noofjobs', label: jslang.noofjobs},
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
                        {type: 'textbox', name: 'jobheight', label: jslang.jobheight},
                        {type: 'textbox', name: 'complogowidth', label: jslang.logowidth},
                        {type: 'textbox', name: 'complogoheight', label: jslang.logoheight},
                        {type: 'listbox', name: 'nofjobsdesktop', label: jslang.noofjobsdesktop,
                            'values': [
                                {text: '1', value: '1'},
                                {text: '2', value: '2'},
                                {text: '3', value: '3'},
                                {text: '4', value: '4'},
                                {text: '5', value: '5'}
                            ]
                        },
                        {type: 'listbox', name: 'nofjobstablet', label: jslang.noofjobstablet,
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
                        var shcode = makeShortcoldejob(e.data);

                        editor.insertContent(shcode);
                    }
                });
            }
        });
    });
})();

function makeShortcoldejob(data) {
    var c = '[wpjobportal_jobs';
    if (data.title != '')
        c += ' title="' + data.title + '"';
    c += ' typeofjobs="' + data.typeofjobs + '"';
    c += ' showtitle="' + data.showtitle + '"';
    c += ' company="' + data.company + '"';
    c += ' companylogo="' + data.companylogo + '"';
    c += ' category="' + data.category + '"';
    c += ' jobtype="' + data.jobtype + '"';
    c += ' location="' + data.location + '"';
    c += ' posted="' + data.posted + '"';
    if (data.noofjobs != '')
        c += ' noofjobs="' + data.noofjobs + '"';
    c += ' listingstyle="' + data.listingstyle + '"';
    c += ' boxstyle="' + data.boxstyle + '"';
    c += ' fieldcolumn="' + data.fieldcolumn + '"';
    if (data.moduleheight != 0)
        c += ' moduleheight="' + data.moduleheight + '"';
    if (data.jobheight != 0)
        c += ' jobheight="' + data.jobheight + '"';
    if (data.complogowidth != 0)
        c += ' complogowidth="' + data.complogowidth + '"';
    if (data.complogoheight != 0)
        c += ' complogoheight="' + data.complogoheight + '"';
    c += ' nofjobsdesktop="' + data.nofjobsdesktop + '"';
    c += ' nofjobstablet="' + data.nofjobstablet + '"';
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