@extends('index')

@section('title')
  Menu management for {{$restaurant->name}}
@endsection

@section('extra-css')

@endsection

@section('content')
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header">
                <ol class="breadcrumb">
                    <li><a href="{{route('homepage')}}">Home</a></li>
                    <li><a href="{{route('restaurant.index')}}">Restaurants</a></li>
                    <li><a href="{{route('restaurant.show', $restaurant->slug)}}">{{$restaurant->name}}</a></li>
                    <li class="active">Menu</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Menu Management
                        <small>Easily create and manage your menu</small>
                    </h2>
                </div>
                <div class="body">
                        @if($restaurant->categories->count() > 0)

                        @foreach($restaurant->categories as $no => $category)
                        <form method="POST" action="{{route('category.update', $restaurant->slug)}}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row clearfix">
                                        <div class="col-sm-3">
                                            <label for="name">Category #{{$no + 1}}</label>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="hidden" class="form-control" name="id" required value="{{$category->id}}">
                                                    <input type="text" class="form-control" name="name" required placeholder="Category name" value="{{$category->name}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="image">Category Image</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    @if($category->category_image)
                                                        <img src="{{$category->category_image}}" width="36" height="36" alt="{{$category->name}}" style="border-radius: 50% !important;">
                                                    @else
                                                        <i class="material-icons">image</i>
                                                    @endif
                                                </span>
                                                <div class="form-line">
                                                    <input type="file" class="form-control" name="image">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="description">Description</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">description</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="description" placeholder="Description" value="{{$category->description}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3" style="text-align: center;">
                                            <label>Actions</label>
                                            <div><a href="{{route('category.delete', ['slug' => $restaurant->slug, 'category_id' => $category->id])}}" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Delete">
                                                <i class="material-icons">delete</i>
                                            </a>&nbsp;<button type="submit" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Save">
                                                <i class="material-icons">save</i>
                                            </button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @endforeach
                        @endif
                        <form method="POST" action="{{route('category.create', $restaurant->slug)}}">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row clearfix">
                                        <div class="col-sm-3">
                                            <label for="name">Create new category</label>
                                            <div class="input-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="name" required placeholder="Category name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="image">Category Image</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">image</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="file" class="form-control" name="image">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="description">Description</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">description</i>
                                                </span>
                                                <div class="form-line">
                                                    <input type="text" class="form-control" name="description" placeholder="Description">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3" style="text-align: center;">
                                            <label>Actions</label>
                                            <div><button type="submit" class="btn btn-default btn-circle waves-effect waves-circle waves-float" style="z-index: 1;" title="Add new category">
                                                <i class="material-icons">add</i>
                                            </button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-script')
        {{Html::script('bsbmd/plugins/jquery-countto/jquery.countTo.js')}}
@endsection