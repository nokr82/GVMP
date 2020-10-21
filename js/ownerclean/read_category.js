/**
 * 아래 예제는 인터넷 브라우저의 콘솔 창에서 실행해볼 수 있습니다.
 * 크롬 브라우저에서 테스트되었습니다.
 *
 * 토큰은 manual.md 파일의 JWT 인증 섹션에 있는 방식으로 발급받을 수 있으며,
 * 이 예제 코드 하단부에 있는 client.setRequestHeader("Authorizaiton", "Bearer YOUR_TOKEN"); 코드의
 * YOUR_TOKEN 부분에 발급받은 토큰을 넣어주시면 됩니다.
 */

/**
 * 이 예제는 ROOT 카테고리에 대한 descendants 정보를 요청해
 * 하위 카테고리 리스트를 불러오는 예시입니다.
 * descendants는 몇 개를 불러올 지 명시하지 않았으므로, 처음 100개를 불러옵니다.
 */
var client = new XMLHttpRequest();

/**
 * 정보를 읽어올 카테고리입니다.
 */
var category = "000000000000";
var readQuery = `
query {
    category(key: "${category}") {
      descendants {
        pageInfo {
          startCursor
          endCursor
        }
        edges {
          cursor
          node {
            key
            name
          }
        }
      }
    }
  }
`;

client.open("GET", "https://api-sandbox.ownerclan.com/v1/graphql?query=" + encodeURIComponent(readQuery), true);
client.setRequestHeader("Authorization", "<?=$TOKEN?>");
client.onreadystatechange = function (aEvt) {
    if (client.readyState === 4) {
        if (client.status === 200) {
            var response = JSON.parse(client.responseText);
            if (response.errors) {
                // 에러가 있다면 에러를 콘솔에 씁니다.
                console.log(JSON.stringify(response.errors));
            } else {
                // 에러가 없다면 반환된 데이터를 콘솔에 씁니다.
                console.log(JSON.stringify(response.data));
            }
        } else {
            // API 서버 응답이 정상이 아닌 경우 에러와 HTTP status code를 콘솔에 씁니다.
            console.error(client.status, client.responseText);
        }
    }
}

client.send(null);