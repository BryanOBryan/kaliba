
$('.back').click(function(){
    window.history.back();
});

$('.forward').click(function(){
    window.history.forward();
});

$('.print').click(function(){
    window.print();
});

$('.datepicker').datepicker({
    format: "dd/mm/yyyy",
    autoclose: true,
    todayHighlight: true
});

$('.datable').DataTable();

$('#checkall').click(function(){
    $('.checkbox').prop('checked', $(this).prop('checked'));
});

$('.checkbox').click(function(){
    if(false === $(this).prop('checked')){
        $('#checkall').prop('checked', false);
    }
    if($('.checkbox:checked').length === $('.checkbox').length){
        $('#checkall').prop('checked', true);
    }
});

$('.action').click(function(e){
    e.preventDefault();    
    var target = $(this).attr('href');
    var checkboxArray = new Array();
    $(".checkbox:checked").each(function(){
        checkboxArray.push($(this).val());
    });
    var selected = checkboxArray.join('/');
    if(selected.length >= 1){
        window.location.href = target+'/'+selected;
    }else{
        window.alert("SelectExpression a record");
        return false;
    }
});

$('.multi-delete').click(function(e){
    e.preventDefault();      
    var checkboxArray = new Array();
    $(".checkbox:checked").each(function(){
        checkboxArray.push($(this).val());
    });
    var selected = checkboxArray.join('/');
    if(selected.length > 0){
        var confirm =  window.confirm("Do you really want to delete?");
        if(confirm === true){ 
            var target = $(this).attr('href');
            window.location.href = target+'/'+selected;
        }  
    }else{
        window.alert("SelectExpression a record");
    }
});

$('.delete').click(function(e){
    e.preventDefault();      
    var confirm =  window.confirm("Do you really want to delete?");
    if(confirm === true){ 
        var target = $(this).attr('href');
        window.location.href = target;
    }  

});

$('#form').submit(function(e){
        e.preventDefault();    
        var target = $(this).attr('action');
        var data = $(this).serialize().split('&').join('/');
        window.location.href = target+data;
    }); 
    
function searchApplicant(){
    var input = $("#mwid").val();
    var token = $("#csrf_token").val();
    var target = $("#searchController").val();
    $.post(target,{mwid: input, csrf_token:token}, function(data){       
        var applicant = JSON.parse(data);
        $('#id').val(applicant.id);
        $('#firstname').val(applicant.firstname);
        $('#surname').val(applicant.surname);
        if(applicant.sex === 'Male'){
            $('#sex-male').prop('checked', true);
        }
        else if(applicant.sex === 'Female'){
            $('#sex-female').prop('checked', true);
        }
        $('#dob').val(applicant.dob);
        $('#email').val(applicant.email);
        $('#telephone').val(applicant.telephone);
        $('#cellphone').val(applicant.cellphone);
        $('#address').val(applicant.address);
    });      
}

function searchStudent(){
    var input = $("#mwid").val();
    var token = $("#csrf_token").val();
    var target = $("#searchController").val();
    $.post(target,{mwid: input, csrf_token:token}, function(data){       
        var student = JSON.parse(data);
        $('#id').val(student.id);
        $('#firstname').val(student.firstname);
        $('#surname').val(student.surname);
        if(student.sex === 'Male'){
            $('#sex-male').prop('checked', true);
        }
        else if(student.sex === 'Female'){
            $('#sex-female').prop('checked', true);
        }
        $('#dob').val(student.dob);
        $('#email').val(student.email);
        $('#telephone').val(student.telephone);
        $('#cellphone').val(student.cellphone);
        $('#address').val(student.address);
        $('#sponsor_name').val(student.sponsor_name);
        $('#sponsor_telephone').val(student.sponsor_telephone);
        $('#sponsor_cellphone').val(student.sponsor_cellphone);
        $('#sponsor_address').val(student.sponsor_address);
    });      
}

function generatePassword(){
    var target = $('#passwordController').val();
    $.get(target, function(data){
        $('#password').val(data); 
    });
}

