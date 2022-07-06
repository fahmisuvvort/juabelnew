<!DOCTYPE html>
<html lang="id" prefix="og: https://ogp.me/ns#">
  <head>
    @if($jsapp['page']['title'])
    <title>{{ $jsapp['page']['title'] }}</title>
    <meta property="og:title" content="{{ $jsapp['page']['title'] }}" data-qmeta="ogTitle">
    @else
    <title>{{ $jsapp['shop']['sitename'] }}</title>
    <meta property="og:title" content="{{ $jsapp['shop']['sitename'] }}" data-qmeta="ogTitle">
    @endif
    @if($jsapp['page']['description'])
    <meta name="description" content="{{ $jsapp['page']['description'] }}">
    <meta property="og:description" content="{{ $jsapp['page']['description'] }}" data-qmeta="ogDescription">
    @else
    <meta name="description" content="{{ $jsapp['shop']['description'] }}">
    <meta property="og:description" content="{{ $jsapp['shop']['description'] }}" data-qmeta="ogDescription">
    @endif

    <meta charset="utf-8">
    <meta name=format-detection content="telephone=no">
    <meta name=msapplication-tap-highlight content=no>
    <meta name=viewport content="user-scalable=no,initial-scale=1,maximum-scale=1,minimum-scale=1,width=device-width">
    <link rel=icon type=image/png href=icon/favicon.png>
    <link rel=icon type=image/png sizes=96x96 href=icon/icon-96x96.png>
    <link rel=icon type=image/png sizes=192x192 href=icon/icon-192x192.png>
    <link rel=icon type=image/png sizes=256x256 href=icon/icon-256x256.png>

    <meta property="og:site_name" content="{{ $jsapp['shop']['sitename'] }}" data-qmeta="ogSitename">

    <meta property="og:type" content="website" data-qmeta="ogType"/>
    @if(isset($jsapp['page']['featured_image']) && $jsapp['page']['featured_image'])
    <meta property="og:image" content="{{ $jsapp['page']['featured_image'] }}" data-qmeta="ogImage">
    @else 
    <meta property="og:image" content="icon/icon-384x384.png" data-qmeta="ogImage">
    @endif
  </head>
  <body class="bg-blue-grey-1">
  @if(isset($jsapp['page']['featured_image']) && $jsapp['page']['featured_image'])
      <link itemprop="thumbnailUrl" href="{{ $jsapp['page']['featured_image'] }}" >
      <span itemprop="thumbnail" itemscope itemtype="https://schema.org/ImageObject">
      <link itemprop="url" href="{{ $jsapp['page']['featured_image'] }}">
      </span>
    @else 
    <link itemprop="thumbnailUrl" href="icon/icon-384x384.png" >
      <span itemprop="thumbnail" itemscope itemtype="https://schema.org/ImageObject">
        <link itemprop="url" href="icon/icon-384x384.png" >
      </span>
    @endif
    <!-- DO NOT touch the following DIV -->
    <div id="q-app"></div>
  </body>
</html>
