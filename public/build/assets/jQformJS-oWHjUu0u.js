$(document).ready(function(){$(".group-input").on("blur",function(){let n=$(this).val(),s=$(this).data("line-id"),e=$(this).data("group-id");$.ajax({url:"/editGroupName",method:"POST",type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},data:{group_name:n,line_id:s,group_id:e},success:function(t){t.status==="success"?console.log("Имя группы успешно обновлено"):alert("Ошибка: "+t.message)},error:function(t,a,o){console.log("Ошибка:",o)}})}),$(".filter-checkbox").change(function(){let n=$(this).val(),s=$(this).data("line-id"),e=$(this).is(":checked");$.ajax({url:"/changeCheckboxStatus",method:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},data:{filter_id:n,line_id:s,status:e},success:function(t){t.status==="success"?console.log("Фильтр успешно обновлен"):console.error("Ошибка при обновлении фильтра: "+t.message)},error:function(t){console.error("Ошибка при выполнении AJAX-запроса")}})}),$(".select-client").on("change",function(){let n=$(this).val(),s=$("#line_id").val();$.ajax({url:"/updateSearchClient",method:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},data:{client_id:n,lineId:s},success:function(e){e.status==="success"?console.log("Клиент успешно обновлен"):console.error("Ошибка при обновлении клиента: "+e.message)},error:function(e){console.error("Ошибка при выполнении AJAX-запроса")}})})});
