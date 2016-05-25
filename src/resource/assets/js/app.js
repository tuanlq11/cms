/**
 * Start App.js
 */
// Script Functions for Permission Module
var permission = {
    updateModuleActionList: function () {
        // Get elements
        var moduleSelectBoxEle = $('.permission-module-list > select');
        // Get module name
        var moduleName = moduleSelectBoxEle.val();
        if(moduleName != ""){
            permission.getModuleActionList(moduleName);
        }
    },
    getModuleActionList: function(module) {
        $.ajax({
            url: "/permission/modules/"+module+"/actions",
            method: 'GET',
            // Process update data list if process is succeed
            success: function (result) {
                var data = $.parseJSON(result);
                if(data){
                    var actionSelectBoxEle = $('.permission-module-action-list > select');
                    // Reset data and data list
                    actionSelectBoxEle.val('');
                    actionSelectBoxEle.html('');
                    actionSelectBoxEle.append('<option value=""></option>');

                    // Process add new data list
                    $.each(data, function(key, value){
                        actionSelectBoxEle.append('<option value="'+key+'">'+value+'</option>');
                    });

                    // Re-aside old value to the data list
                    var previousData = actionSelectBoxEle.data('previous');
                    actionSelectBoxEle.val(previousData);
                }
            } ,
            error: function (){
                Console.log("Cannot get the module action data list.");
            },
        });
    }
}
$(document).ready(function() {
    $('form').on('submit', function(){
        // Disable all buttons & button style elements when an button click action is occurred
        $('.btn').each(function () {
            $(this).addClass('disabled');
        });
    });
    console.log($('.permission-module-action-list > select').val());
    permission.updateModuleActionList();

    $('.permission-module-list > select').on('change', function(){
        permission.updateModuleActionList();
    });
});
/**
 *  End App.js
 */