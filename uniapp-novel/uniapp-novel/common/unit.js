// 基本参数
let screenWidth = uni.getSystemInfoSync().windowWidth; // 屏幕宽度
// #ifdef H5
let screenHeight = uni.getSystemInfoSync().screenHeight; // 屏幕高度 
// #endif
// #ifndef H5
let screenHeight = uni.getSystemInfoSync().windowHeight; // 屏幕高度 
// #endif
// 基本方法	
const Torpx = num => 750 * num / screenWidth, // px转rpx
	Topx = num => num * screenWidth / 750; // rpx转px

const getSystemHeight = ({
	isRpx = true
}) => isRpx ? Torpx(screenHeight) : screenHeight; // 获取屏幕高度


// 过滤器方法 (时间戳转时间)
const formatMusicTime = num => {
	let divisionNum = Math.floor(num / 60),
		remainderNum = Math.floor(num % 60),
		zero = (x) => '0'.repeat(2 - String(x).length);
	return `${zero(divisionNum)+divisionNum}:${zero(remainderNum)+remainderNum}`
}



//以下方法在生命周期mounted以及mounted之后调用,不支持nvue, 用pos -> className
// 获取各节点的高度信息
const getNodesHeightInfo = optionObj => {
	let {
		pageID,
		pos,
		success
	} = optionObj;
	let heightArr = []; // 用于接收各节点高度
	const query = uni.createSelectorQuery().in(pageID);
	query.selectAll(`.${pos}`).boundingClientRect(data => {
		data.forEach(item => heightArr.push(item.height))
		success(heightArr)
	}).exec()


}

// 计算剩余高度
const calSurplusHeight = optionObj => {
	let {
		pageID,
		pos,
		isRpx = true,
		isTabBarPage = false,
		success
	} = optionObj;

	getNodesHeightInfo({
		pageID,
		pos,
		success: NodesHeightArr => {

			// 累加已使用高度
			let usedTotalHeight = NodesHeightArr.reduce((pre, item) => {
				return pre + item;
			});

			// 计算剩余高度(默认为非tabbar页面) 针对app端
			let SurHeight = isTabBarPage ? screenHeight - usedTotalHeight - 50 : screenHeight -
				usedTotalHeight;
			// console.log(screenHeight)
			// console.log(Torpx(SurHeight) );
			// #ifdef MP-WEIXIN
			SurHeight += 50;
			// #endif
			// 判断是否转换为rpx形式(默认转换)
			SurHeight = isRpx ? Torpx(SurHeight) : SurHeight;
			// 取整(防止震动)
			let SurHeightEND = Math.floor(SurHeight)
			success(SurHeightEND)
		}
	})
}


// 将query对象转成url参数
export function queryParams(query) {

	// console.log(query)

	let q = [];
	for (let key in query) {
		if (query[key]) {
			q.push(`${key}=${encodeURIComponent(query[key])}`);
		}
	}
	let r = q.join('&');



	r = r ? '?' + r : '';
	// console.log(r);
	return r;
}





export default {
	Torpx, //px转rpx
	Topx, //rpx转px
	getSystemHeight, //获取屏幕高度
	formatMusicTime, //过滤器方法
	getNodesHeightInfo, //获取各节点高度信息
	calSurplusHeight, //计算剩余高度
}
