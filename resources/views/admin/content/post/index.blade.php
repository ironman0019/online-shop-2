@extends('admin.layouts.master')

@section('head-tag')
    <title>پست ها</title>
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item font-size-12"> <a href="#">خانه</a></li>
            <li class="breadcrumb-item font-size-12"> <a href="#">بخش محتوی</a></li>
            <li class="breadcrumb-item font-size-12 active" aria-current="page">پست ها</li>
        </ol>
    </nav>


    <section class="row">
        <section class="col-12">
            <section class="main-body-container">
                <section class="main-body-container-header">
                    <h5>
                        پست ها
                    </h5>
                </section>

                <section class="d-flex justify-content-between align-items-center mt-4 mb-3 border-bottom pb-2">
                    <a href="{{ route('admin.content.post.create') }}" class="btn btn-info btn-sm">ایجاد پست </a>
                    <div class="max-width-16-rem">
                        <input type="text" class="form-control form-control-sm form-text" placeholder="جستجو">
                    </div>
                </section>

                <section class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان پست</th>
                                <th>دسته</th>
                                <th>تصویر</th>
                                <th>تگ ها</th>
                                <th>اسلاگ</th>
                                <th>اسم نویسنده</th>
                                <th>وضعیت</th>
                                <th>امکان درج کامنت</th>
                                <th>تاریخ ساخت</th>
                                <th>تاریخ انتشار</th>
                                <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> تنظیمات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->postCategory->name }}</td>
                                    <td>
                                        <img src="{{ $post->image ? asset($post->image['indexArray'][$post->image['currentImage']]) : 'خالی' }}"
                                            alt="{{ $post->title }}" width="50" height="50">
                                    </td>
                                    <td>{{ $post->tags }}</td>
                                    <td>{{ $post->slug }}</td>
                                    <td>{{ $post->user->first_name }}</td>
                                    <td>
                                        <button type="button" id="{{ $post->id }}"
                                            onclick="changeStatus({{ $post->id }})"
                                            data-url="{{ route('admin.content.post.status', $post->id) }}"
                                            class="btn btn-sm status-toggle {{ $post->status === 1 ? 'btn-success' : 'btn-secondary' }}">
                                            <i class="fa {{ $post->status === 1 ? 'fa-check' : 'fa-times' }}"></i>
                                            
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" id="{{ $post->id . "-commentable" }}"
                                            onclick="changeCommentable({{ $post->id }})"
                                            data-url="{{ route('admin.content.post.commentable', $post->id) }}"
                                            class="btn btn-sm status-toggle {{ $post->commentable === 1 ? 'btn-success' : 'btn-secondary' }}">
                                            <i class="fa {{ $post->commentable === 1 ? 'fa-check' : 'fa-times' }}"></i>
                                            
                                        </button>
                                    </td>
                                    <td>
                                        {{ Morilog\Jalali\Jalalian::forge($post->created_at)->format('%A, %d %B %y') }}
                                    </td>
                                    <td>
                                        {{ Morilog\Jalali\Jalalian::forge($post->published_at)->format('%A, %d %B %y') }}
                                    </td>
                                    <td class="width-16-rem text-left">
                                        <a href="{{ route('admin.content.post.edit', $post) }}" class="btn btn-primary btn-sm"><i
                                                class="fa fa-edit"></i>
                                            ویرایش
                                        </a>
                                        <form action="{{ route('admin.content.post.destroy', $post->id) }}"
                                            class="d-inline"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm delete">
                                                <i class="fa fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </section>

            </section>
        </section>
        <!-- Toast wrapper for any remaining toast functionality -->
        <div class="toast-wrapper position-fixed" style="top: 20px; right: 20px; z-index: 9999;"></div>
    </section>
@endsection


@section('scripts')
    @parent
    <script type="text/javascript">
        function changeStatus(id) {
            var element = $("#" + id)
            var url = element.attr('data-url')
            var isActive = element.hasClass('btn-success');

            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    if (response.status) {
                        if (response.checked) {
                            // Set to active
                            element.removeClass('btn-secondary').addClass('btn-success');
                            element.find('i').removeClass('fa-times').addClass('fa-check');
                            element.contents().filter(function() {
                                return this.nodeType === 3; // Text nodes
                            }).remove();
                            element.append(' فعال');
                            successToast('پست فعال شد')
                        } else {
                            // Set to inactive
                            element.removeClass('btn-success').addClass('btn-secondary');
                            element.find('i').removeClass('fa-check').addClass('fa-times');
                            element.contents().filter(function() {
                                return this.nodeType === 3; // Text nodes
                            }).remove();
                            element.append(' غیرفعال');
                            successToast('پست با موفقیت غیر فعال شد')
                        }
                    } else {
                        errorToast('هنگام ویرایش مشکلی بوجود امده است')
                    }
                },
                error: function() {
                    errorToast('ارتباط برقرار نشد')
                }
            });
        }

        function changeCommentable(id) {
            var element = $("#" + id + "-commentable")
            var url = element.attr('data-url')
            var isActive = element.hasClass('btn-success');

            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    if (response.status) {
                        if (response.checked) {
                            // Set to active
                            element.removeClass('btn-secondary').addClass('btn-success');
                            element.find('i').removeClass('fa-times').addClass('fa-check');
                            element.contents().filter(function() {
                                return this.nodeType === 3; // Text nodes
                            }).remove();
                            element.append(' فعال');
                            successToast('امکان درج کامنت با موفقیت فعال شد')
                        } else {
                            // Set to inactive
                            element.removeClass('btn-success').addClass('btn-secondary');
                            element.find('i').removeClass('fa-check').addClass('fa-times');
                            element.contents().filter(function() {
                                return this.nodeType === 3; // Text nodes
                            }).remove();
                            element.append(' غیرفعال');
                            successToast('امکان درج کامنت با موفقیت غیر فعال شد')
                        }
                    } else {
                        errorToast('هنگام ویرایش مشکلی بوجود امده است')
                    }
                },
                error: function() {
                    errorToast('ارتباط برقرار نشد')
                }
            });
        }

        function successToast(message) {
            Swal.fire({
                title: 'موفقیت!',
                text: message,
                icon: 'success',
                confirmButtonText: 'باشه'
            });
        }

        function errorToast(message) {
            Swal.fire({
                title: 'خطا!',
                text: message,
                icon: 'error',
                confirmButtonText: 'باشه'
            });
        }
    </script>

    @include('admin.alerts.sweetalert.success')
    @include('admin.alerts.sweetalert.delete-confirm', ['className' => 'delete'])
@endsection
