<section id="fluidGridSystem">
  <div class="page-header">
    <h1>Fluid grid system <small>12 responsive, percent-based columns</small></h1>
  </div>

  <h2>Fluid columns</h2>
  <div class="row-fluid show-grid">
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
  </div>
  <div class="row-fluid show-grid">
    <div class="span4">4</div>
    <div class="span4">4</div>
    <div class="span4">4</div>
  </div>
  <div class="row-fluid show-grid">
    <div class="span4">4</div>
    <div class="span8">8</div>
  </div>
  <div class="row-fluid show-grid">
    <div class="span6">6</div>
    <div class="span6">6</div>
  </div>
  <div class="row-fluid show-grid">
    <div class="span12">12</div>
  </div>

  <div class="row">
    <div class="span4">
      <h3>Percents, not pixels</h3>
      <p>The fluid grid system uses percents for column widths instead of fixed pixels. It also has the same responsive variations as our fixed grid system, ensuring proper proportions for key screen resolutions and devices.</p>
    </div><!-- /.span -->
    <div class="span4">
      <h3>Fluid rows</h3>
      <p>Make any row fluid simply by changing <code>.row</code> to <code>.row-fluid</code>. The columns stay the exact same, making it super straightforward to flip between fixed and fluid layouts.</p>
    </div><!-- /.span -->
    <div class="span4">
      <h3>Markup</h3>
      <pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"row-fluid"</span><span class="tag">&gt;</span></li><li class="L1"><span class="pln">  </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span4"</span><span class="tag">&gt;</span><span class="pln">...</span><span class="tag">&lt;/div&gt;</span></li><li class="L2"><span class="pln">  </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span8"</span><span class="tag">&gt;</span><span class="pln">...</span><span class="tag">&lt;/div&gt;</span></li><li class="L3"><span class="tag">&lt;/div&gt;</span></li></ol></pre>
    </div><!-- /.span -->
  </div><!-- /.row -->

  <h2>Fluid nesting</h2>
  <div class="row">
    <div class="span6">
      <p>Nesting with fluid grids is a bit different: the number of nested columns doesn't need to match the parent. Instead, your columns are reset at each level because each row takes up 100% of the parent column.</p>
      <div class="row-fluid show-grid">
        <div class="span12">
          Fluid 12
          <div class="row-fluid show-grid">
            <div class="span6">
              Fluid 6
            </div>
            <div class="span6">
              Fluid 6
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.span -->
    <div class="span6">
      <pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"row-fluid"</span><span class="tag">&gt;</span></li><li class="L1"><span class="pln">  </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span12"</span><span class="tag">&gt;</span></li><li class="L2"><span class="pln">    Level 1 of column</span></li><li class="L3"><span class="pln">    </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"row-fluid"</span><span class="tag">&gt;</span></li><li class="L4"><span class="pln">      </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span6"</span><span class="tag">&gt;</span><span class="pln">Level 2</span><span class="tag">&lt;/div&gt;</span></li><li class="L5"><span class="pln">      </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span6"</span><span class="tag">&gt;</span><span class="pln">Level 2</span><span class="tag">&lt;/div&gt;</span></li><li class="L6"><span class="pln">    </span><span class="tag">&lt;/div&gt;</span></li><li class="L7"><span class="pln">  </span><span class="tag">&lt;/div&gt;</span></li><li class="L8"><span class="tag">&lt;/div&gt;</span></li></ol></pre>
    </div><!-- /.span -->
  </div><!-- /.row -->

</section>
