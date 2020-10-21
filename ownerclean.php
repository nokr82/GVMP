<?
include_once ('./_common.php');
include_once ('./dbConn.php');

$row = sql_fetch_array(sql_query("SELECT * FROM g5_config"));
$TOKEN = $row['cf_10'];
?>

<script type="text/javascript">
<!--
/**
 * 아래 예제는 인터넷 브라우저의 콘솔 창에서 실행해볼 수 있습니다.
 * 크롬 브라우저에서 테스트되었습니다.
 *
 * 토큰은 manual.md 파일의 JWT 인증 섹션에 있는 방식으로 발급받을 수 있으며,
 * 이 예제 코드 하단부에 있는 client.setRequestHeader("Authorizaiton", "Bearer YOUR_TOKEN"); 코드의
 * YOUR_TOKEN 부분에 발급받은 토큰을 넣어주시면 됩니다.
*/
var client = new XMLHttpRequest();
var readQuery = `
query {
	allItems {
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
				returnCriteria
				metadata
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
//-->
</script>