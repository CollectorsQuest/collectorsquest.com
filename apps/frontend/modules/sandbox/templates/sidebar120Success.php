
<section id="gridSystem">
  <div class="page-header">
    <h1>Default grid system <small>12 columns with a responsive twist</small></h1>
  </div>

  <div class="row show-grid">
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
  <div class="row show-grid">
    <div class="span4">4</div>
    <div class="span4">4</div>
    <div class="span4">4</div>
    <div class="span4">4</div>
    <div class="span4">4</div>
  </div>
  <div class="row show-grid">
    <div class="span4">4</div>
    <div class="span8">8</div>
  </div>
  <div class="row show-grid">
    <div class="span6">6</div>
    <div class="span6">6</div>
  </div>
  <div class="row show-grid">
    <div class="span12">12</div>
  </div>
  <div class="row">
    <div class="span4">
      <p>The default grid system provided as part of Bootstrap is a <strong>940px-wide, 12-column grid</strong>.</p>
      <p>It also has four responsive variations for various devices and resolutions: phone, tablet portrait, table landscape and small desktops, and large widescreen desktops.</p>
    </div><!-- /.span -->
    <div class="span4">
      <pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"row"</span><span class="tag">&gt;</span></li><li class="L1"><span class="pln">  </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span4"</span><span class="tag">&gt;</span><span class="pln">...</span><span class="tag">&lt;/div&gt;</span></li><li class="L2"><span class="pln">  </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span8"</span><span class="tag">&gt;</span><span class="pln">...</span><span class="tag">&lt;/div&gt;</span></li><li class="L3"><span class="tag">&lt;/div&gt;</span></li></ol></pre>
    </div><!-- /.span -->
    <div class="span4">
      <p>As shown here, a basic layout can be created with two "columns," each spanning a number of the 12 foundational columns we defined as part of our grid system.</p>
    </div><!-- /.span -->
  </div><!-- /.row -->

  <br>

  <h2>Offsetting columns</h2>
  <div class="row show-grid">
    <div class="span4">4</div>
    <div class="span4 offset4">4 offset 4</div>
  </div><!-- /row -->
  <div class="row show-grid">
    <div class="span3 offset3">3 offset 3</div>
    <div class="span3 offset3">3 offset 3</div>
  </div><!-- /row -->
  <div class="row show-grid">
    <div class="span8 offset4">8 offset 4</div>
  </div><!-- /row -->
  <pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"row"</span><span class="tag">&gt;</span></li><li class="L1"><span class="pln">  </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span4"</span><span class="tag">&gt;</span><span class="pln">...</span><span class="tag">&lt;/div&gt;</span></li><li class="L2"><span class="pln">  </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span4 offset4"</span><span class="tag">&gt;</span><span class="pln">...</span><span class="tag">&lt;/div&gt;</span></li><li class="L3"><span class="tag">&lt;/div&gt;</span></li></ol></pre>

  <br>

  <h2>Nesting columns</h2>
  <div class="row">
    <div class="span6">
      <p>With the static (non-fluid) grid system in Bootstrap, nesting is easy. To nest your content, just add a new <code>.row</code> and set of <code>.span*</code> columns within an existing <code>.span*</code> column.</p>
      <h3>Example</h3>
      <p>Nested rows should include a set of columns that add up to the number of columns of it's parent. For example, two nested <code>.span3</code> columns should be placed within a <code>.span6</code>.</p>
      <div class="row show-grid">
        <div class="span6">
          Level 1 of column
          <div class="row show-grid">
            <div class="span3">
              Level 2
            </div>
            <div class="span3">
              Level 2
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.span -->
    <div class="span6">
      <pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"row"</span><span class="tag">&gt;</span></li><li class="L1"><span class="pln">  </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span12"</span><span class="tag">&gt;</span></li><li class="L2"><span class="pln">    Level 1 of column</span></li><li class="L3"><span class="pln">    </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"row"</span><span class="tag">&gt;</span></li><li class="L4"><span class="pln">      </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span6"</span><span class="tag">&gt;</span><span class="pln">Level 2</span><span class="tag">&lt;/div&gt;</span></li><li class="L5"><span class="pln">      </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span6"</span><span class="tag">&gt;</span><span class="pln">Level 2</span><span class="tag">&lt;/div&gt;</span></li><li class="L6"><span class="pln">    </span><span class="tag">&lt;/div&gt;</span></li><li class="L7"><span class="pln">  </span><span class="tag">&lt;/div&gt;</span></li><li class="L8"><span class="tag">&lt;/div&gt;</span></li></ol></pre>
    </div><!-- /.span -->
  </div><!-- /.row -->
</section>

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


