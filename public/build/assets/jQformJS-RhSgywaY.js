$(document).ready(function(){$(".group-input").on("blur",function(){let t=$(this).val(),s=$(this).data("line-id"),e=$(this).data("group-id");$.ajax({url:"/editGroupName",method:"POST",type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},data:{group_name:t,line_id:s,group_id:e},success:function(n){n.status==="success"?console.log("Имя группы успешно обновлено"):alert("Ошибка: "+n.message)},error:function(n,a,o){console.log("Ошибка:",o)}})}),$(".filter-checkbox").change(function(){let t=$(this).val(),s=$(this).data("line-id"),e=$(this).is(":checked");$.ajax({url:"/changeCheckboxStatus",method:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},data:{filter_id:t,line_id:s,status:e},success:function(n){n.status==="success"?console.log("Фильтр успешно обновлен"):console.error("Ошибка при обновлении фильтра: "+n.message)},error:function(n){console.error("Ошибка при выполнении AJAX-запроса")}})}),$(".select-client").on("change",function(){let t=$(this).val(),s=$("#line_id").val();$.ajax({url:"/updateSearchClient",method:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},data:{client_id:t,lineId:s},success:function(e){e.status==="success"?console.log("Клиент успешно обновлен"):console.error("Ошибка при обновлении клиента: "+e.message)},error:function(e){console.error("Ошибка при выполнении AJAX-запроса")}})}),$(".select_source").on("change",function(){var t=$(this).val(),s=$(this).attr("id").split("_")[1];$.ajax({url:"/updateSource",method:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},data:{sourceId:t,lineId:s},success:function(e){e.status==="success"?console.log("Клиент успешно обновлен"):alert("Ошибка при обновлении клиента: "+e.message)},error:function(e){console.error("Ошибка при выполнении AJAX-запроса")}})})});
