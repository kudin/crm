$(function () {

    $('#reservation').daterangepicker(null, function (start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });
            
    $('#userid').change(function () {
        val = $(this).val();
        $('#userprojects').html('');
        for(id in usersToProjects[val]) {
            $('#userprojects').append('<option value="' + id + '">' + projects[usersToProjects[val][id]].name + '</option>'); 
        }
    }); 
    $('#userid').change();
}); 
 