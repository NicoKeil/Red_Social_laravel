var url = 'http://localhost/master-php/aprendiendo-php/primeraweb/blog/public';

window.addEventListener("load", function(){
$('.btn-like').css('cursor', 'pointer');
        $('.btn-dislike').css('cursor', 'pointer');
        //boton de like
                function like(){
                $('.btn-like').unbind('click').click(function(){
                console.log('Like');
                        $(this).addClass('btn-dislike').removeClass('btn-like');
                        $(this).attr('src', url+'/img/heart-red.png');
                        $.ajax({
                        url: url + '/like/' + $(this).data('id'),
                                type: "GET",
                                success: function(response){
                                if (response.like){
                                console.log('Has dado like');
                                } else{
                                console.log('Error al dar like');
                                }
                                }
                        });
                        dislike();
                });
                }
        like();
                //boton de dislike
                        function dislike(){
                        $('.btn-dislike').unbind('click').click(function(){
                        console.log('Dislike');
                                $(this).addClass('btn-like').removeClass('btn-dislike');
                                $(this).attr('src',  url+'/img/heart-gray.png');
                                like();
                                $.ajax({
                                url: url + '/dislike/' + $(this).data('id'),
                                        type: "GET",
                                        success: function(response){
                                        if (response.like){
                                        console.log('Has dado dislike');
                                        } else{
                                        console.log('Error al dar dislike');
                                        }
                                        }
                                });
                            like();       
                        }); 
                    
                                } 
          dislike();
          
          //Buscador
          
          $('#buscador').submit(function(){
              
              $(this).attr('action',url+'/gente/'+$('#buscador #search').val());
             
          });
  
});