
/**
 *  Postcodify - 도로명주소 우편번호 검색 프로그램 (그누보드 플러그인)
 * 
 *  Copyright (c) 2014, Kijin Sung <root@poesis.kr>
 *  
 *  이 프로그램은 자유 소프트웨어입니다. 이 소프트웨어의 피양도자는 자유
 *  소프트웨어 재단이 공표한 GNU 약소 일반 공중 사용 허가서 (GNU LGPL) 제3판
 *  또는 그 이후의 판을 임의로 선택하여, 그 규정에 따라 이 프로그램을
 *  개작하거나 재배포할 수 있습니다.
 * 
 *  이 프로그램은 유용하게 사용될 수 있으리라는 희망에서 배포되고 있지만,
 *  특정한 목적에 맞는 적합성 여부나 판매용으로 사용할 수 있으리라는 묵시적인
 *  보증을 포함한 어떠한 형태의 보증도 제공하지 않습니다. 보다 자세한 사항에
 *  대해서는 GNU 약소 일반 공중 사용 허가서를 참고하시기 바랍니다.
 * 
 *  GNU 약소 일반 공중 사용 허가서는 이 프로그램과 함께 제공됩니다.
 *  만약 허가서가 누락되어 있다면 자유 소프트웨어 재단으로 문의하시기 바랍니다.
 */

(function($) {
    
    // 검색 단추를 찾는다.
    
    var button = $("button[onclick^='win_zip']");
    var plugin_path = (typeof g5_url !== "undefined" ? g5_url : "") + "/plugin/postcodify";
    
    if (!button.size() && typeof g4_url !== "undefined") {
        button = $("a[onclick^='win_zip']");
        plugin_path = g4_url + "/extend/postcodify";
    }
    
    // 일단 검색 단추를 사용하지 못하도록 한다.
    
    button.attr("disabled", "disabled");
    
    // 기존의 주소검색 이벤트를 제거하고 Postcodify 팝업 열기 코드로 대체한다.
    
    setTimeout(function() {
        button.unbind("click").removeAttr("onclick").removeAttr("disabled");
        button.click(function(event) {
            var url = plugin_path + "/zip.html";
            window.open(url, "winZip", "left=50,top=50,width=648,height=632,resizable=yes,scrollbars=yes");
            event.preventDefault();
        });
    }, 200);
    
} (jQuery));
