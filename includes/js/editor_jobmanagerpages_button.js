(function () {
    tinymce.PluginManager.add('wpjobportalmanager_jmpages_button', function (editor, url) {
        editor.addButton('wpjobportalmanager_jmpages_button', {
            title: jslang.main_jmpages_title,
            text: jslang.main_jmpages_text,
            icon: 'icon wpjobportal-ouricon-img',
            onclick: function () {
                editor.windowManager.open({
                    title: jslang.window_jmpages_title,
                    width: 500,
                    height: 200,
                    autoScroll: true,
                    classes: 'wpjobportaljobseditorclass',
                    body: [
                        {
                            type: 'listbox', 
                            name: 'page', 
                            label: jslang.selectpage, 
                            'values': [
                                {text : jslang.jobseeker_cp , value : '1'} ,
                                {text : jslang.newestjobs , value : '2'} ,
                                {text : jslang.myappliedjobs , value : '3'} ,
                                {text : jslang.myresumes , value : '4'} ,
                                {text : jslang.jobsearch , value : '5'} ,
                                {text : jslang.josbycategory , value : '6'} ,
                                {text : jslang.addresume , value : '8'} ,
                                {text : jslang.shortlistedjobs , value : '9'} ,
                                {text : jslang.jobseekermessages , value : '10'} ,
                                {text : jslang.listcompanies , value : '11'} ,
                                {text : jslang.jobsavesearch , value : '12'} ,
                                {text : jslang.jobalert , value : '13'} ,
                                {text : jslang.jobseekercredits , value : '14'} ,
                                {text : jslang.jobseekercreditslog , value : '15'} ,
                                {text : jslang.jobseekerratelist , value : '16'} ,
                                {text : jslang.purchasehistoryjob , value : '17'} ,
                                {text : jslang.jobseekerstats , value : '18'} ,
                                {text : jslang.employer_cp , value : '19'} ,
                                {text : jslang.myjobs , value : '20'} ,
                                {text : jslang.addjob , value : '21'} ,
                                {text : jslang.resumesearch , value : '22'} ,
                                {text : jslang.resumesbycategory , value : '23'} ,
                                {text : jslang.mycompanies , value : '24'} ,
                                {text : jslang.addcompany , value : '25'} ,
                                {text : jslang.employermessages , value : '26'} ,
                                {text : jslang.resumesavesearch , value : '27'} ,
                                {text : jslang.employercredits , value : '28'} ,
                                {text : jslang.employercreditslog , value : '29'} ,
                                {text : jslang.employerratelist , value : '30'} ,
                                {text : jslang.purchasehistoryemp , value : '31'} ,
                                {text : jslang.employerstats , value : '32'} ,
                                {text : jslang.login , value : '33'} ,
                                {text : jslang.empregister , value : '34'},
                                {text : jslang.jkregister , value : '35'},
                                {text : jslang.thankyou , value : '36'},
                                /*{text : jslang.listjobs , value : '1'} ,
                                {text : jslang.main_searchjob_title , value : '2'} ,
                                {text : jslang.myjobs , value : '4'} ,
                                {text : jslang.myprofile , value : '5'} ,
                                {text : jslang.jobdetail , value : '6'} ,
                                {text : jslang.jobsbytype , value : '8'} ,
                                {text : jslang.companydetail , value : '11'} ,
                                {text : jslang.purchasehistoryemp , value : '12'} ,
                                */

                            ],
                            onselect : function(v) {                                
                                if(this.value() == 15){
                                    jQuery('input.mce-cm_buttonforty').parent().parent().show();
                                }else{
                                    jQuery('input.mce-cm_buttonforty').parent().parent().hide();
                                }
                            },
                        },
                        {type: 'textbox', name: 'title', label: jslang.title , classes: 'jsjm_buttonforty',},
                        {type: 'textbox', name: 'message', label: jslang.message , classes: 'jsjm_buttonforty',},
                    ],
                    onsubmit: function (e) {
                        var shcode = makeShortcoldejmpages(e.data);
                        editor.insertContent(shcode);
                    }
                });
            }
        });
    });
})();

function makeShortcoldejmpages(data) {

    var c = '[jm_job_manager_pages';
    if (data.page != '')
        c += ' page="' + data.page + '"';
    if (data.title != '')
        c += ' title="' + data.title + '"';
    if (data.message != '')
        c += ' message="' + data.message + '"';

    c += ']';
    return c;
}