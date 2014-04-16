<?php
<div class="cell">
    <div class="cell_head_left"></div>
    <div class="cell_head_right"></div>
    <div class="clearfloat"></div>
    <div class="cell_columns">
        <div class= "cell_left_col"></div>
        <div class= "cell_centre_col">
            <div class="cell_row1"></div>
            <div class="cell_row2"></div>
            <div class="cell_row3"></div>
            <div class="cell_row4"></div>
            <div class="cell_row5"></div>
        </div>
        <div class="cell_right_col"></div>
    </div>
    <div class="clearfloat"></div>
    <div class="cell_foot_left"></div>
    <div class="cell_foot_right"></div>
    <div class="clearfloat"></div>
</div>

.cell {
    width:30%;
    background:#555;
    padding:1ex;
    box-sizing:border-box;
}
.cell_head,
.cell_footer {
    height:40px;
    background:#aaa;
    margin:10px;
}
.cell_left_col,
.cell_centre_col,
.cell_right_col {
background:#ccc;
    min-height:200px;
    width:29%;
    float:left;
    margin:2%;
    box-sizing:border-box;
}
.cell_row1,
.cell_row2,
.cell_row3,
.cell_row4,
.cell_row5 {
    background:#eee;
    box-sizing:border-box;
    height:50px;
    margin:0px;
    border:solid 1px #777;
}
.clearfloat {
    clear:both;
}
.cell_head_left,
.cell_head_right,
.cell_foot_left,
.cell_foot_right {
    width:48%;
    margin:1%;
    height:40px;
    background:#aaa;
    float:left;
}
.cell_columns {
    display:block;
    clear:both;
}


