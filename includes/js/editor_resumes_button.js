(function () {
    tinymce.PluginManager.add('wpjobportal_resumes_button', function (editor, url) {
        editor.addButton('wpjobportal_resumes_button', {
            title: jslang.main_resume_title,
            text: jslang.main_resume_text,
            icon: 'icon wpjobportal-ouricon-img',
            onclick: function () {
                editor.windowManager.open({
                    title: jslang.window_resume_title,
                    width: 500,
                    height: 400,
                    autoScroll: true,
                    classes: 'wpjobportaljobseditorclass',
                    body: [
                        {type: 'textbox', name: 'title', label: jslang.title},
                        {type: 'listbox', name: 'typeofresume', label: jslang.typeofresume,
                            'values': [
                                {text: 'Select type', value: '0'},
                                {text: 'Newest resumes', value: '1'},
                                {text: 'Top resumes', value: '2'},
                                {text: 'Featured resumes', value: '4'}
                            ]
                        },
                        {type: 'listbox', name: 'showtitle', label: jslang.showtitle,
                            'values': [
                                {text: 'Show', value: '1'},
                                {text: 'Hide', value: '0'}
                            ]
                        },
                        {type: 'listbox', name: 'applicationtitle', label: jslang.applicationtitle,
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
                        {type: 'listbox', name: 'name', label: jslang.name,
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
                        {type: 'listbox', name: 'experience', label: jslang.experience,
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
                        {type: 'listbox', name: 'resumephoto', label: jslang.photo,
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
                        {type: 'listbox', name: 'available', label: jslang.available,
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
                        {type: 'listbox', name: 'gender', label: jslang.gender,
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
                        {type: 'listbox', name: 'nationality', label: jslang.nationality,
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
                        {type: 'textbox', name: 'noofresume', label: jslang.noofresume},
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
                        {type: 'textbox', name: 'resumeheight', label: jslang.resumeheight},
                        {type: 'textbox', name: 'logowidth', label: jslang.logowidth},
                        {type: 'textbox', name: 'logoheight', label: jslang.logoheight},
                        {type: 'listbox', name: 'nofresumedesktop', label: jslang.noofresumesdesktop,
                            'values': [
                                {text: '1', value: '1'},
                                {text: '2', value: '2'},
                                {text: '3', value: '3'},
                                {text: '4', value: '4'},
                                {text: '5', value: '5'}
                            ]
                        },
                        {type: 'listbox', name: 'nofresumetablet', label: jslang.noofresumetablet,
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
                        var shcode = makeShortcolderesume(e.data);
                        editor.insertContent(shcode);
                    }
                });
            }
        });
    });
})();

function makeShortcolderesume(data) {
    var c = '[wpjobportal_resumes';
    if (data.title != '')
        c += ' title="' + data.title + '"';
    c += ' typeofresume="' + data.typeofresume + '"';
    c += ' showtitle="' + data.showtitle + '"';
    c += ' applicationtitle="' + data.applicationtitle + '"';
    c += ' name="' + data.name + '"';
    c += ' category="' + data.category + '"';
    c += ' jobtype="' + data.jobtype + '"';
    c += ' experience="' + data.experience + '"';
    c += ' resumephoto="' + data.resumephoto + '"';
    c += ' available="' + data.available + '"';
    c += ' gender="' + data.gender + '"';
    c += ' nationality="' + data.nationality + '"';
    c += ' location="' + data.location + '"';
    c += ' posted="' + data.posted + '"';
    if (data.noofresume != '')
        c += ' noofresume="' + data.noofresume + '"';
    c += ' listingstyle="' + data.listingstyle + '"';
    c += ' boxstyle="' + data.boxstyle + '"';
    c += ' fieldcolumn="' + data.fieldcolumn + '"';
    if (data.moduleheight != 0)
        c += ' moduleheight="' + data.moduleheight + '"';
    if (data.resumeheight != 0)
        c += ' resumeheight="' + data.resumeheight + '"';
    if (data.logowidth != 0)
        c += ' logowidth="' + data.logowidth + '"';
    if (data.logoheight != 0)
        c += ' logoheight="' + data.logoheight + '"';
    c += ' nofresumedesktop="' + data.nofresumedesktop + '"';
    c += ' nofresumetablet="' + data.nofresumetablet + '"';
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