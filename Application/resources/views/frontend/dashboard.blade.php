<!DOCTYPE html>
<html lang="{{ getLang() }}">

<head>
    @section('title', $SeoConfiguration->title ?? '')
    @include('frontend.global.includes.head')
    @include('frontend.global.includes.styles')
</head>

<body style="margin:0px;padding:0px;overflow:hidden">
    <iframe src="https://datastudio.google.com/embed/reporting/b2c8745b-5d67-4af2-97e4-8a9defc3b69f/page/4c37C" frameborder="0" style="overflow:hidden;overflow-x:hidden;overflow-y:hidden;height:100%;width:100%;position:absolute;top:0px;left:0px;right:0px;bottom:0px" height="100%" width="100%"></iframe>
</body>

</html>
