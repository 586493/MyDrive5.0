@inject('d3', 'App\SessionData')

<div class="container">

    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--c06); border: 1px solid var(--c04);">

                <!-- Modal header -->
                <div class="modal-header" style="border: 1px solid var(--c04);">
                    <h4 class="modal-title" style="color: var(--c01) !important;">
                        <span id="modalH01">
                            @include("modal_header.modal_header_01")
                        </span>
                        <span id="modalH02">
                            @include("modal_header.modal_header_02")
                        </span>
                        <span id="modalH03">
                            @include("modal_header.modal_header_03")
                        </span>
                        <span id="modalH04">
                            @include("modal_header.modal_header_04")
                        </span>
                        <span id="modalH05">
                            @include("modal_header.modal_header_05")
                        </span>
                        <span id="modalH06">
                            @include("modal_header.modal_header_06")
                        </span>
                    </h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body"
                     style="background-color: var(--c05); border: 1px solid var(--c04); color: var(--c01) !important;">
                    <div id="modalB01">
                        @include("modal_body.modal_body_01")
                    </div>
                    <div id="modalB02">
                        @include("modal_body.modal_body_02")
                    </div>
                    <div id="modalB03">
                        @include("modal_body.modal_body_03")
                    </div>
                    <div id="modalB04">
                        @include("modal_body.modal_body_04")
                    </div>
                    <div id="modalB05">
                        @include("modal_body.modal_body_05")
                    </div>
                    <div id="modalB06">
                        @include("modal_body.modal_body_06")
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer" style="border: 1px solid var(--c04); color: var(--c01) !important;">
                    <div id="modalF01">
                        @include("modal_footer.modal_footer_01")
                    </div>
                    <div id="modalF02">
                        @include("modal_footer.modal_footer_02")
                    </div>
                    <div id="modalF03">
                        @include("modal_footer.modal_footer_03")
                    </div>
                    <div id="modalF04">
                        @include("modal_footer.modal_footer_04")
                    </div>
                    <div id="modalF05">
                        @include("modal_footer.modal_footer_05")
                    </div>
                    <div id="modalF06">
                        @include("modal_footer.modal_footer_06")
                    </div>
                    <button type="button" class="btn modalFooterBtn" data-dismiss="modal">
                        {{$closeBtnText}}
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>
