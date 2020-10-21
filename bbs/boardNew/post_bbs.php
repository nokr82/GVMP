<!DOCTYPE html>
<html>

<head>
  <title>일반글쓰기</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

  <link rel="stylesheet" href="../../css/normalize.css">
  <link rel="stylesheet" href="../../css/board.css">
</head>

<body>
  <!-- 파일 첨부 일반 게시판 -->
  <main role="main">
    <div class="container_bbs write_post">
      <div class="head_area">
        <h2 class="bbs_tit"><span class="blind">자유게시판 글쓰기</span></h2>
        <div class="post_head">
          <label for="notice_yn" class="notice_check check_contain">공지
            <input type="checkbox" id="notice_yn" name="notice_yn" />
            <span class="checkmark"></span>
          </label>
          <div class="post_tit_box">
            <input type="text" placeholder="제목" />
          </div>
        </div>
      </div>
      <div class="cont_area">
        <div class="bbs_post_area">
          <form method="post">
            <textarea class="post_editor"></textarea>
          </form>
        </div>
        <div class="post_ft_area">
          <div class="wrap_url_upload">
            <div class="inp_box link_box">
              <i class="icon_link"><span class="blind">링크 아이콘</span></i>
              <input type="url" />
            </div>
            <div class="inp_box upload_box">
              <i class="icon_fileup"><span class="blind">업로드 아이콘</span>
              </i>
              <span class="file_txt">올린 파일 이름</span>
              <div class="upload_custom">
                <label for="fileup">파일선택</label>
                <input type="file" id="fileup" name="fileup" />
              </div>
            </div>
          </div>
          <div class="wrap_url_upload">
            <div class="inp_box link_box">
              <i class="icon_link"><span class="blind">링크 아이콘</span></i>
              <input type="url" />
            </div>
            <div class="inp_box upload_box">
              <i class="icon_fileup"><span class="blind">업로드 아이콘</span>
              </i>
              <span class="file_txt">올린 파일 이름</span>
              <div class="upload_custom">
                <label for="fileup">파일선택</label>
                <input type="file" id="fileup" name="fileup" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="tail_area">
        <span class="btn_list_back" onclick="window.location.href='list.php'">목록</span>
        <span class="btn_cnxl_posting" onclick="window.location.href='list.php'">취소</span>
        <span class="btn_complete" onclick="window.location.href='modify_bbs.php'">작성완료</span>
      </div>
    </div>
  </main>
  <script>
  $(document).ready(function() {
    $('.post_editor').summernote({
      height: 500,
      lang: 'ko-KR',
      toolbar: [
        ['insert', ['picture']],
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['view', ['fullscreen', 'help']],
      ],
      placeholder: '이곳에 내용을 작성해주세요',
      fontsize: 16
    });
  });
  </script>
</body>

</html>