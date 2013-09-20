@extends('layouts.application')

@section('pageName')
Chrome Button
@stop

@section('content')

  <div class="container">
    <div class="row-fluid">
      <section>
        <div class="page-header">
          <h3>Chrome Extension</h3>
        </div>

        <p>This is a simple extension for the Google Chrome and Chromium browsers which adds a simple link to <span class="brand">the drop</span> into your <em>Apps</em> page.</p>
        <p>In order to install it,</p>
        <ul>
          <li>{{ link_to_asset('bin/thedrop_chromeapp.crx', 'Download the extension.') }}</li>
          <li>Locate the extension file in your file explorer or simply go to your <strong>Downloads</strong> page (<code>Ctrl + J</code>) and click on the <code>Show in folder</code> link beneath the download.</li>
          <li>Open up the <strong>Extensions</strong> page in Chrome by launching <code><a href="chrome://extensions">chrome://extensions</a></code>.</li>
          <li>Drag-and-drop the extension file onto the <strong>Extensions</strong> page and follow the Chrome prompt.</li>
        </ul>

        <img src="{{ asset('img/chromeapp_screen.png') }}" class="img-polaroid" style="width:866px;height:668px;margin:30px" title="Screenshot showcasing the app button in Chrome.">
      </section>
    </div>
  </div>

@stop