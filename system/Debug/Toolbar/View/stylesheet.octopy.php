<style type="text/css">
    #debug-icon {
        position   : fixed;
        bottom     : 0;
        right      : 0;
        width      : 36px;
        height     : 36px;
        background : #f0f0f0;
        border     : 1px solid #ddd;
        margin     : 0px;
        z-index    : 10000;
        box-shadow : 0 -3px 10px rgba(0, 0, 0, 0.1);
        clear      : both;
        text-align : center;
    }

    #debug-icon a img {
        margin     : 4px;
        max-width  : 26px;
        max-height : 26px;
    }

    #debug-bar a:active, #debug-bar a:link, #debug-bar a:visited {
        color : #dd4814;
    }

    #debug-bar {
        font-family : "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size   : 16px;
        font-weight : 400;
        line-height : 36px;
        background  : #fff;
        position    : fixed;
        bottom      : 0;
        left        : 0;
        right       : 0;
        height      : 36px;
        z-index     : 10000;
    }

    #debug-bar h1,
    #debug-bar h2,
    #debug-bar h3 {
        font-family : "Helvetica Neue", Helvetica, Arial, sans-serif;
        color       : #000;
        line-height : 1.5;
        font-weight : bold;
    }

    #debug-bar p {
        font-size : 12px;
        margin    : 0 0 10px 20px;
        padding   : 0;
    }

    #debug-bar a {
        text-decoration : none;
        outline         : 0;
    }

    #debug-bar a:hover {
        text-decoration       : none;
        text-decoration-color : #4e4a4a;
    }

    #debug-bar .muted,
    #debug-bar .muted td {
        color : #bbb;
    }

    #debug-bar .toolbar {
        display     : block;
        background  : inherit;
        overflow    : hidden;
        overflow-y  : auto;
        white-space : nowrap;
        box-shadow  : 0 -3px 10px rgba(0, 0, 0, 0.1);
        padding     : 0 12px 0 12px; /* give room for OS X scrollbar */
        z-index     : 10000;
    }

    #debug-bar #toolbar-position > a {
        padding : 0 6px;
    }

    #debug-icon.fixed-top,
    #debug-bar.fixed-top {
        top    : 0;
        bottom : auto;
    }

    #debug-icon-link, .octopy-history-load {
        outline : 0;
    }

    #debug-icon.fixed-top,
    #debug-bar.fixed-top .toolbar {
        box-shadow : 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    #debug-bar h1 {
        font-size   : 16px;
        line-height : 36px;
        font-weight : 500;
        margin      : 0 16px 0 0;
        padding     : 0;
        text-align  : left;
        display     : inline-block;
        position    : absolute;
        right       : 30px;
        top         : 0;
        bottom      : 0;
    }

    #debug-bar-link {
        padding  : 0 6px;
        position : absolute;
        display  : inline-block;
        top      : 0;
        bottom   : 0;
        right    : 10px;

    }

    #debug-bar h2 {
        font-size   : 16px;
        font-weight : 500;
        margin      : 0;
        padding     : 0;
    }

    #debug-bar h2 span {
        font-size : 13px;
    }

    #debug-bar h3 {
        text-transform : uppercase;
        font-size      : 11px;
        font-weight    : 200;
        margin-left    : 10pt;
    }

    #debug-bar span.octopy-label {
        display        : inline-block;
        font-size      : 14px;
        line-height    : 36px;
        vertical-align : baseline;
    }

    #debug-bar span.octopy-label img {
        display : inline-block;
        margin  : 6px 3px 6px 0;
        float   : left;
        clear   : left;
    }

    #debug-bar span.octopy-label .badge {
        display          : inline-block;
        padding          : 3px 6px;
        font-size        : 75%;
        font-weight      : 500;
        line-height      : 1;
        color            : #fff;
        text-align       : center;
        white-space      : nowrap;
        vertical-align   : baseline;
        border-radius    : 10rem;
        background-color : #0C1021;
        margin-left      : 0.5em;
    }

    #debug-bar span.octopy-label .badge.active {
        background-color : red;
    }

    #debug-bar button {
        border           : 1px solid #ddd;
        background-color : #fff;
        cursor           : pointer;
        border-radius    : 4px;
        color            : #333;
    }

    #debug-bar button:hover {
        background-color : #eaeaea;
    }

    #debug-bar tr[data-active="1"] {
        background-color : #dff0d8;
    }

    #debug-bar tr[data-active="1"]:hover {
        background-color : #a7d499;
    }

    #debug-bar tr.current {
        background-color : #FDC894;
    }

    #debug-bar tr.current:hover {
        background-color : #DD4814;
    }

    #debug-bar table strong {
        font-weight : 500;
        color       : rgba(0, 0, 0, 0.3);
    }

    #debug-bar .octopy-label {
        text-shadow : none;
    }

    #debug-bar .octopy-label:hover {
        background-color : #eaeaea;
        cursor           : pointer;
    }

    #debug-bar .octopy-label a {
        display         : block;
        padding         : 0 10px;
        color           : inherit;
        text-decoration : none;
        letter-spacing  : normal;
    }

    #debug-bar .octopy-label.active {
        background-color : #eaeaea;
        border-color     : #bbb;
    }

    #debug-bar .tab {
        display      : none;
        background   : inherit;
        padding      : 1em 2em;
        border       : solid #ddd;
        border-width : 1px 0;
        position     : fixed;
        bottom       : 35px;
        left         : 0;
        right        : 0;
        z-index      : 9999;
        box-shadow   : 0 -3px 10px rgba(0, 0, 0, 0.1);
        overflow     : hidden;
        overflow-y   : auto;
        max-height   : 62%;
    }

    #debug-bar.fixed-top .tab {
        top        : 36px;
        bottom     : auto;
        box-shadow : 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    #debug-bar table {
        margin          : 0 0 10px 20px;
        font-size       : 0.9rem;
        border-collapse : collapse;
        width           : 100%;
    }

    #debug-bar td,
    #debug-bar th {
        display    : table-cell;
        text-align : left;
    }

    #debug-bar tr {
        border : none;
    }

    #debug-bar td {
        border  : none;
        padding : 0 10px 0 5px;
        margin  : 0;
    }

    #debug-bar th {
        /*padding-bottom: 0.7em;*/
    }

    #debug-bar tr td:first-child {
        max-width : 20%;
    }

    #debug-bar tr td:first-child.narrow {
        width : 7em;
    }

    #debug-bar tr:hover {
        background-color : #f3f3f3;
    }

    #debug-bar table.timeline {
        width       : 100%;
        margin-left : 0;
    }

    #debug-bar table.timeline th {
        font-size      : 0.7em;
        font-weight    : 200;
        text-align     : left;
        padding-bottom : 1em;
    }

    #debug-bar table.timeline td,
    #debug-bar table.timeline th {
        border-left : 1px solid #ddd;
        padding     : 0 1em;
        position    : relative;
    }

    #debug-bar table.timeline tr td:first-child,
    #debug-bar table.timeline tr th:first-child {
        border-left  : 0;
        padding-left : 0;
    }

    #debug-bar table.timeline td {
        padding : 5px;
    }

    #debug-bar table.timeline .timer {
        position         : absolute;
        display          : inline-block;
        padding          : 5px;
        top              : 40%;
        border-radius    : 4px;
        background-color : #999;
    }

    #debug-bar .route-params,
    #debug-bar .route-params-item {
        vertical-align : top;
    }

    #debug-bar .route-params-item td:first-child {
        padding-left : 1em;
        text-align   : right;
        font-style   : italic;
    }

    .debug-view.show-view {
        border : 1px solid #dd4814;
        margin : 4px;
    }

    .debug-view-path {
        background-color : #fdc894;
        color            : #000;
        padding          : 2px;
        font-family      : monospace;
        font-size        : 11px;
        min-height       : 16px;
        text-align       : left;
    }

    .show-view .debug-view-path {
        display : block !important;
    }

    @media screen and (max-width : 748px) {
        .hide-sm {
            display : none !important;
        }
    }

    /**
    simple styles to replace inline styles
     */
    .debug-bar-txt-bold {
        font-weight : bold;
    }

    .debug-bar-width30 {
        width : 30%;
    }

    .debug-bar-width10 {
        width : 10%;
    }

    .debug-bar-width70p {
        width : 70px;
    }

    .debug-bar-width140p {
        width : 140px;
    }

    .debug-bar-width20e {
        width : 20em;
    }

    .debug-bar-width6r {
        width : 6rem;
    }

    .debug-bar-ndisplay {
        display : none;
    }

    .debug-bar-alignRight {
        text-align : right;
    }

    .debug-bar-alignLeft {
        text-align : left;
    }

    .debug-bar-noverflow {
        overflow : hidden;
    }
</style>
