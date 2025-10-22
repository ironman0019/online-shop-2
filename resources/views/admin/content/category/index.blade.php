@extends('admin.layouts.master')

@section('head-tag')
    <title>دسته بندی</title>
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item font-size-12"> <a href="#">خانه</a></li>
            <li class="breadcrumb-item font-size-12"> <a href="#">بخش فروش</a></li>
            <li class="breadcrumb-item font-size-12 active" aria-current="page"> دسته بندی</li>
        </ol>
    </nav>


    <section class="row">
        <section class="col-12">
            <section class="main-body-container">
                <section class="main-body-container-header">
                    <h5>
                        دسته بندی
                    </h5>
                </section>


                <section class="d-flex justify-content-between align-items-center mt-4 mb-3 border-bottom pb-2">
                    <a href="{{ route('admin.content.category.create') }}" class="btn btn-info btn-sm">ایجاد دسته بندی</a>
                    <div class="max-width-16-rem">
                        <input type="text" class="form-control form-control-sm form-text" placeholder="جستجو">
                    </div>
                </section>

                <section class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>نام دسته بندی</th>
                                <th>توضیحات</th>
                                <th>اسلاگ</th>
                                <th>عکس</th>
                                <th>تگ ها</th>
                                <th>وضعیت</th>
                                <th>تاریخ ایجاد</th>
                                <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> تنظیمات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ Str::limit($category->description, 20) }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>
                                        <img src="{{ $category->image }}" alt="{{ $category->name }}" width="50"
                                            height="50">
                                    </td>
                                    <td>{{ $category->tags }}</td>
                                    <td>
                                        <button type="button" id="{{ $category->id }}"
                                            onclick="changeStatus({{ $category->id }})"
                                            data-url="{{ route('admin.content.category.status', $category->id) }}"
                                            class="btn btn-sm status-toggle {{ $category->status === 1 ? 'btn-success' : 'btn-secondary' }}">
                                            <i class="fa {{ $category->status === 1 ? 'fa-check' : 'fa-times' }}"></i>
                                            {{ $category->status === 1 ? 'فعال' : 'غیرفعال' }}
                                        </button>
                                    </td>
                                    <td>
                                        {{ Morilog\Jalali\Jalalian::forge($category->created_at)->format('%A, %d %B %y') }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mx-2">
                                                <a href="{{ route('admin.content.category.edit', $category) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i> ویرایش
                                                </a>
                                            </div>
                                            <div class="mx-2">
                                                <form action="{{ route('admin.content.category.destroy', $category->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm delete">
                                                        <i class="fa fa-trash"></i> حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
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
        // Handle success alert
        @if (session('swal-success'))
            $(document).ready(function() {
                Swal.fire({
                    title: 'عملیات با موفقیت انجام شد',
                    text: '{{ session('swal-success') }}',
                    icon: 'success',
                    confirmButtonText: 'باشه'
                });
            });
        @endif

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
                            successToast('دسته بندی با موفقیت فعال شد')
                        } else {
                            // Set to inactive
                            element.removeClass('btn-success').addClass('btn-secondary');
                            element.find('i').removeClass('fa-check').addClass('fa-times');
                            element.contents().filter(function() {
                                return this.nodeType === 3; // Text nodes
                            }).remove();
                            element.append(' غیرفعال');
                            successToast('دسته بندی با موفقیت غیر فعال شد')
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


    <script>
        $(document).ready(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault();

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-2',
                        cancelButton: 'btn btn-danger mx-2'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'آیا از حذف کردن داده مطمن هستید؟',
                    text: "شما میتوانید درخواست خود را لغو نمایید",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'بله داده حذف شود.',
                    cancelButtonText: 'خیر درخواست لغو شود.',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value == true) {
                        $(this).parent().submit();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: 'لغو درخواست',
                            text: "درخواست شما لغو شد",
                            icon: 'error',
                            confirmButtonText: 'باشه.'
                        })
                    }
                })
            })
        });
    </script>
@endsection
