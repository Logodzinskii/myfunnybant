@extends('admin.layouts.adminHome')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/sunny/jquery-ui.css">
<script text="text/javascript">
    $(document).ready(function(){

        const uniqId = (() => {
            let i = 0;
            return () => {
                return i++;
            }
        })();

        class Block
        {
            constructor(id){
                this.id = id;
            }
            layoutHeader()
            {
                let develop = $('#develope');
                let input = '<div class="mb-3"><label for="input'+ this.id +'" class="form-label">Введите заголовок поста</label><input type="text" id="input'+ this.id +'" class="form-control" placeholder="заголовок поста"></div>';
                develop.append(input);
                let previwe = $('#previwe');
                previwe.append('<h1 id="output'+ this.id +'" class="p-3"></h1>');
                let id = this.id;
                $('#input'+ this.id).on('change', function(){
                    $('#output'+ id).text(this.value);
                });
            }
            layoutImage(orientation)
            {
                let id = this.id;
                let addImgInput = '<div><input type="file" name="" id="myImage'+id+'" class="form-control" placeholder="Выберите картинку"/></div>';
                let develop = $('#develope');
                develop.append(addImgInput);


                
                $("#myImage"+id).on('change', function(){
                    //console.log(this.value);
                    

                    var myFormData = new FormData();
                    myFormData.append('image', this.files[0]);

                    

                    $.ajax({
                        url: '/admin/blog/save/image',
                        type: 'POST',
                        processData: false, // important
                        contentType: false, // important
                        dataType : 'json',
                        data: myFormData,
                        success: function(data){                        
                            //console.log(data);
                            let image = '';
                            if(orientation === 'album'){
                                image = image + '<figure class="figure">';
                            }else{
                                image = image + '<figure class="figure col-6">';
                            }
                            image = image + '<img id="imputImg'+id+'" name="image" src="#" class="figure-img img-fluid rounded" alt="your image" />';
                            image = image + '<figcaption class="figure-caption"></figcaption>';
                            image = image + '</figure>';   
                            let previwe = $('#previwe');
                            
                            previwe.append(image);
                            //previwe.append('<h2>Done</h2>');
                            $('#imputImg'+id+'').attr('src', data);
                       
                        },
                        error:function (error) {
                            console.log(error);
                        }
                    });
                                     
                    //readURL(this);
                });

                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#imputImg'+id+'').attr('src', e.target.result);
                        }
                    reader.readAsDataURL(input.files[0]);
                    }
                }
            } 
            layoutBody()
            {
                let develop = $('#develope');
                let input = '<div class="mb-3">';
                    input = input + '<label for="input'+ this.id +'" class="form-label">Введите текст поста</label>';
                    input = input + '<textarea class="form-control" name="" id="input'+ this.id +'" rows="10"></textarea>';
                    input = input + '</div>';
                
                //let input = '<div class="mb-3"><label for="input'+ this.id +'" class="form-label">Введите текст поста</label><input type="text" id="input'+ this.id +'" class="form-control" placeholder="заголовок поста"></div>';
                develop.append(input);
                let previwe = $('#previwe');
                previwe.append('<p id="output'+ this.id +'" class="fs-3 lh-lg p-3"></p>');
                let id = this.id;
                $('#input'+ this.id).on('change', function(){
                    $('#output'+ id).html(this.value);
                });
            }           

            save()
            {
                let previwe = $('#previwe');

                $('#blog').val(previwe.html());

            }
        };
        

        $('.addHeader').on('click',function(){
            let id = $('.addHeader').uniqueId();
            let block = new Block(uniqId());
            block.layoutHeader();
        });
        $('.addImage').on('click', function(){
            let block = new Block(uniqId());
            block.layoutImage('album');
        });
        $('.addImageBook').on('click', function(){
            let block = new Block(uniqId());
            block.layoutImage('book');
        });
        $('.addText').on('click', function(){
            let block = new Block(uniqId());
            block.layoutBody();
        });

        $('.saveblock').on('click',function(){
            let block = new Block();
            block.save();
        });
    })
</script>
<div
    class="container"
>
<div class="card">
    <div class="card-header">
        <h2>Раздел создания блога</h2>
    </div>
    <div class="card-body">
        <button class="btn btn-primary addHeader">Добавить заголовок</button> 
        <button class="btn btn-primary addImage">Добавить картинку альбомную</button>
        <button class="btn btn-primary addImageBook">Добавить картинку книжный формат</button>
        <button class="btn btn-primary addText">Добавить текст</button>

        <button class="btn btn-primary saveblock">К отправке</button> 
        <form action="{{route('create.blog.post')}}" method="post" class="pt-3 pb-3" name="form">
            @csrf
        <div class="card text-start bg-light">
            <div class="card-body d-flex justyfy-content-center flex-wrap">
                <div class="mb-3">
                    <label for="blog_category" class="form-label">Категория</label>
                    <select
                        class="form-select form-select-lg"
                        name="blog_category"
                        id="blog_category"
                    >
                        <option selected>Выберите</option>
                        <option value="творчество">творчество</option>
                        <option value="события">события</option>
                    </select>
                </div>
                
                <div class="col-md-4 p-2">
                    <label for="" class="form-label">Author</label>
                    <input type="text" name="blog_author_name" class="form-control" value="" required />
                </div>
                <div class="col-md-4 p-2">
                    <label for="" class="form-label">Ссылки на автора</label>
                    <input type="text" name="blog_author_link" class="form-control" value="" required />
                </div>
                <div class="col-md-4 p-2">
                    <label for="" class="form-label">Название страницы Seo meta-tag</label>
                    <input type="text" name="blog_header" class="form-control" value="" required />
                </div>
                <div class="col-md-4 p-2">
                    <label for="" class="form-label">Описание seo meta-tag</label>
                    <input type="text" name="blog_desrypion" class="form-control" value="" required />
                </div>
            </div>
        </div>       

        <div class="mb-3">
            <label for="blog" class="form-label"></label>
            <textarea class="form-control" name="blog" id="blog" rows="6"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Отправить на сервер</button>
        </form> 
        <h2>Ввод информации</h2>
        <div id='develope' class="mt-3 mb-3">
        </div>
    </div>
</div>
<h2>Раздел предварительного просмотра</h2>
<div id="previwe" class="card">
     
</div>
</div>

    
@endsection