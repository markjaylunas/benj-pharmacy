<script src="https://code.jquery.com/jquery-3.6.0.min.js""></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script> -->
<script src="js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#orderDatatable').DataTable({
            "pagingType": "full_numbers",
            lengthMenu: [
                [10,1,5,15,25,50,-1],
                [10,1,5,15,25,50,"All"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search for",
            }
        });
        $('#featuredDatatable').DataTable({
            "pagingType": "full_numbers",
            lengthMenu: [
                [10,1,5,15,25,50,-1],
                [10,1,5,15,25,50,"All"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search for",
            }
        });
        $('#exclusiveDatatable').DataTable({
            "pagingType": "full_numbers",
            lengthMenu: [
                [10,1,5,15,25,50,-1],
                [10,1,5,15,25,50,"All"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search for",
            }
        });
        $('#orderEditDatatable').DataTable({
            "pagingType": "full_numbers",
            lengthMenu: [
                [-1,1,5,10,15,25,50],
                ["All",1,5,10,15,25,50]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search for",
            }
        });

        $('#usersDatatable').DataTable({
            "pagingType": "full_numbers",
            lengthMenu: [
                [10,1,5,15,25,50,-1],
                [10,1,5,15,25,50,"All"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search for",
            }
        });
        $('#productsDatatable').DataTable({
            "pagingType": "full_numbers",
            lengthMenu: [
                [10,1,5,15,25,50,-1],
                [10,1,5,15,25,50,"All"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search for",
            }
        });
        $('#categoriesDatatable').DataTable({
            "pagingType": "full_numbers",
            lengthMenu: [
                [10,1,5,15,25,50,-1],
                [10,1,5,15,25,50,"All"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search for",
            }
        });
        $("#summernotePADes").summernote({
            placeholder: "Type your description",
            height: 100,
            fontNames: ['Poppins'],
            fontNamesIgnoreCheck: ['Poppins'],
            fontSizes: ['8', '9', '10', '11', '12', '13', '14', '15', '16', '18', '20', '22' , '24', '28', '32', '36', '40', '48'],
            followingToolbar: false,
            dialogsInBody: true,
            toolbar: [
                ['style'],
                ['style', ['clear', 'bold', 'italic', 'underline']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],       
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['codeview']]
            ]
        });
        $("#summernotePASpe").summernote({
            placeholder: "Type your description",
            height: 100,
            fontNames: ['Poppins'],
            fontNamesIgnoreCheck: ['Poppins'],
            fontSizes: ['8', '9', '10', '11', '12', '13', '14', '15', '16', '18', '20', '22' , '24', '28', '32', '36', '40', '48'],
            followingToolbar: false,
            dialogsInBody: true,
            toolbar: [
                ['style'],
                ['style', ['clear', 'bold', 'italic', 'underline']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],       
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['codeview']]
            ]
        });
        $("#summernotePEDes").summernote({
            placeholder: "Type your description",
            height: 100,
            fontNames: ['Poppins'],
            fontNamesIgnoreCheck: ['Poppins'],
            fontSizes: ['8', '9', '10', '11', '12', '13', '14', '15', '16', '18', '20', '22' , '24', '28', '32', '36', '40', '48'],
            followingToolbar: false,
            dialogsInBody: true,
            toolbar: [
                ['style'],
                ['style', ['clear', 'bold', 'italic', 'underline']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],       
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['codeview']]
            ]
        });
        $("#summernotePESpe").summernote({
            placeholder: "Type your description",
            height: 100,
            fontNames: ['Poppins'],
            fontNamesIgnoreCheck: ['Poppins'],
            fontSizes: ['8', '9', '10', '11', '12', '13', '14', '15', '16', '18', '20', '22' , '24', '28', '32', '36', '40', '48'],
            followingToolbar: false,
            dialogsInBody: true,
            toolbar: [
                ['style'],
                ['style', ['clear', 'bold', 'italic', 'underline']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],       
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['codeview']]
            ]
        });
        $('.dropdown-toggle').dropdown();
    });
</script>
</body>
</html>