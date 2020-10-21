/**
 * 아래 예제는 인터넷 브라우저의 콘솔 창에서 실행해볼 수 있습니다.
 * 크롬 브라우저에서 테스트되었습니다.
 *
 * 토큰은 manual.md 파일의 JWT 인증 섹션에 있는 방식으로 발급받을 수 있으며,
 * 이 예제 코드 하단부에 있는 client.setRequestHeader("Authorizaiton", "Bearer YOUR_TOKEN"); 코드의
 * YOUR_TOKEN 부분에 발급받은 토큰을 넣어주시면 됩니다.
 */

/**
 * 이 예제는 allItems 쿼리로 검색을 진행할 수 있도록 합니다.
 *
 * ----------
 *
 * `readQuery1`을 제외한 나머지 쿼리 예제에서는 dateFrom이 쓰이지 않는다면
 * dateFrom 파라미터의 값을 0으로 항상 명시했습니다.
 * 이는 dateFrom 파라미터의 값이 0이면 실제 시간으로 1970-01-01 00:00:00이므로
 * 등록된 모든 상품에 대해 검색하기 위함입니다.
 * 테스팅 환경이 아닌 정식 환경에서 사용할 경우에는 적절한 값을 사용해주시면 됩니다.
 *
 * ----------
 *
 * `readQuery1`: 상품 수정일 검색(90일 전부터 30일 전까지)
 * `readQuery2`: 가격 검색(10000원 이상, 20000원 이하)
 * `readQuery3`: 벤더 코드 검색(실제로 검색할 벤더를 넣어주시면 됩니다.)
 * `readQuery4`: 카테고리 코드 검색(카테고리 코드가 101006001007)
 * `readQuery5`: 복합 검색(가격은 50000원 이하이고 최근 60일간 등록된 상품 중에서 카테고리 코드가 101006001007인 상품)
 * `readQuery6`: 페이지네이션1(주어진 cursor 값 이후의 상품 중 첫 500개 불러오기)
 * `readQuery7`: 페이지네이션2(주어진 cursor 값 이전의 상품 중 마지막 100개 불러오기)
 * `readQuery8`: 페이지네이션 + 검색(readQuery5의 조건에 페이지네이션2의 조건을 추가)
 */

/**
 * 예제 6
 */

var client = new XMLHttpRequest();

/**
 * 이 cursor 값 이후의 상품들 중에서만 가져옵니다.
 */
var startCursor = "5000";

var readQuery = `
query {
    allItems(dateFrom: 0, after: "${startCursor}", first: 500) {
      pageInfo {
        hasNextPage
        hasPreviousPage
        startCursor
        endCursor
      }
      edges {
        cursor
        node {
          createdAt
          updatedAt
          key
          name
          model
          production
          origin
          id
          price
          pricePolicy
          fixedPrice
          searchKeywords
          category {
            key
            name
          }
          content
          sellerOnlyContent
          shippingFee
          shippingType
          images(size: large)
          status
          options {
            optionAttributes {
              name
              value
            }
            price
            quantity
            key
          }
          taxFree
          adultOnly
          returnable
          noReturnReason
          guaranteedShippingPeriod
          openmarketSellable
          boxQuantity
          attributes
          closingTime
          metadata
        }
      }
    }
  }
`;

client.open("GET", "https://api-sandbox.ownerclan.com/v1/graphql?query=" + encodeURIComponent(readQuery), true);
client.setRequestHeader("Authorization", "Bearer YOUR_TOKEN");
client.onreadystatechange = function (aEvt) {
    if (client.readyState === 4) {
        if (client.status === 200) {
            var response = JSON.parse(client.responseText);
            if (response.errors) {
                // API 서버 응답이 정상이지만 API 에러가 있다면 에러를 콘솔에 씁니다.
                console.error(JSON.stringify(response.errors));
            } else {
                // API 서버 응답도 정상이고, API 에러도 없다면 반환된 데이터를 콘솔에 씁니다.
                console.log(JSON.stringify(response.data));
            }
        } else {
            // API 서버 응답이 정상이 아닌 경우 에러와 HTTP status code를 콘솔에 씁니다.
            console.error(client.status, client.responseText);
        }
    }
}

client.send(null);