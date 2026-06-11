@extends('admin.layouts.master')

@section('head-tag')
    <title>پیج ساز</title>
@endsection

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item font-size-12"> <a href="#">خانه</a></li>
            <li class="breadcrumb-item font-size-12"> <a href="#">بخش محتوا</a></li>
            <li class="breadcrumb-item font-size-12 active" aria-current="page"> پیج ساز</li>
        </ol>
    </nav>


    <section class="row">
        <section class="col-12">
            <section class="main-body-container">
                <section class="main-body-container-header">
                    <h5>
                        پیج ساز
                    </h5>
                </section>

                <section class="d-flex justify-content-between align-items-center mt-4 mb-3 border-bottom pb-2">
                    <a href="{{ route('admin.content.page.create') }}" class="btn btn-info btn-sm">ایجاد پیج جدید</a>
                    <div class="max-width-16-rem">
                        <input type="text" class="form-control form-control-sm form-text" placeholder="جستجو">
                    </div>
                </section>

                <section class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان </th>
                                <th>تگ</th>
                                <th>اسلاگ</th>
                                <th>وضعیت</th>
                                <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> تنظیمات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pages as $page)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td>{{ $page->title }}</td>
                                    <td>{{ $page->tags }}</td>
                                    <td>{{ $page->slug }}</td>
                                    <td>
                                        <button type="button" id="{{ $page->id }}"
                                            onclick="changeStatus({{ $page->id }})"
                                            data-url="{{ route('admin.content.page.status', $page->id) }}"
                                            class="btn btn-sm status-toggle {{ $page->status === 1 ? 'btn-success' : 'btn-secondary' }}">
                                            <i class="fa {{ $page->status === 1 ? 'fa-check' : 'fa-times' }}"></i>
                                            {{ $page->status === 1 ? 'فعال' : 'غیرفعال' }}
                                        </button>
                                    </td>
                                    <td class="width-16-rem text-left">
                                        <div class="d-flex align-items-center">
                                            <div class="mx-2">
                                                <a href="{{ route('admin.content.page.edit', $page) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i> ویرایش
                                                </a>
                                            </div>
                                            <div class="mx-2">
                                                <form action="{{ route('admin.content.page.destroy', $page->id) }}"
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
                    <div>
                        {{ $pages->links() }}
                    </div>
                </section>

            </section>
        </section>
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
                            successToast('پیج فعال شد')
                        } else {
                            // Set to inactive
                            element.removeClass('btn-success').addClass('btn-secondary');
                            element.find('i').removeClass('fa-check').addClass('fa-times');
                            element.contents().filter(function() {
                                return this.nodeType === 3; // Text nodes
                            }).remove();
                            element.append(' غیرفعال');
                            successToast('پیج با موفقیت غیر فعال شد')
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
