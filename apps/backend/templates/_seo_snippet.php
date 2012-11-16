<div id="seosnippet">
  <a class="title" href="<?= $href ?>" target="_blank"><?= cqStatic::truncateText($title, 70); ?></a><br>
  <a href="<?= $href ?>" class="url" target="_blank">
    <?= cqStatic::truncateText(str_replace('http://', '', $href), 80); ?>
  </a> - <a href="#" class="util">Cached</a>
  <p class="desc">
    <span style="color: rgb(136, 136, 136); ">
      <?= cqStatic::truncateText($description, 156); ?>
    </span>
  </p>
</div>

<style>
  #seosnippet {
    margin: 0 0 10px 0;
    padding: 0 5px;
    font-family: arial, sans-serif;
    line-height: 15px !important;
    font-size: 13px !important;
    font-style: normal;
    width: 42em !important;
  }

  #seosnippet td {
    padding: 0;
    margin: 0;
  }

  #seosnippet cite.url {
    font-weight: normal;
    font-style: normal;
  }

  #seosnippet a {
    text-decoration: none;
  }

  #seosnippet .title {
    color: #11c;
    font-size: 16px !important;
    line-height: 19px;
    text-decoration: underline;
  }

  #seosnippet .desc {
    color: #000;
    font-size: 13px;
    line-height: 15px;
  }

  #seosnippet .url {
    color: #282;
    font-size: 13px;
    line-height: 15px;
  }

  #seosnippet .meta {
    color: #767676;
  }

  #seosnippet .util {
    color: #4272DB;
  }

  #seosnippet p {
    margin: 0 !important;
  }

  #seosnippet a:hover {
    text-decoration: underline;
  }

  #seosnippet {
    margin-bottom: 10px;
  }
</style>
