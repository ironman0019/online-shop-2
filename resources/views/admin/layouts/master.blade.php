<!DOCTYPE html>
<html lang="fa">

<head>
    @include('admin.layouts.head-tag')
</head>

<body dir="rtl">


    @include('admin.layouts.partials.header')

    <section class="body-container">

        @include('admin.layouts.partials.sidebar')

        <section id="main-body" class="main-body">

            @yield('content')

        </section>

    </section>


    @include('admin.layouts.scripts')
</body>

</html>