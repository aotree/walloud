// wall nameにfocus
document.getElementById("name").focus();

// 英語対応
var language = (window.navigator.languages && window.navigator.languages[0]) ||
                window.navigator.language ||
                window.navigator.userLanguage ||
                window.navigator.browserLanguage;
if (language == 'ja' || language == 'ja-JP' || language == 'ja-JP-mac') {
  // japanese
  document.getElementById("description").innerHTML = "壁に付箋を貼ろう！";
  document.getElementById("app_store_url").href = "https://itunes.apple.com/jp/app/walloud/id1363044566?mt=8";
  document.getElementById("app_store_url").style = "display:inline-block;overflow:hidden;background:url(https://linkmaker.itunes.apple.com/assets/shared/badges/ja-jp/appstore-lrg.svg) no-repeat;width:135px;height:40px;background-size:contain;";
} else {
  // japanese以外
  document.getElementById("description").innerHTML = "Let's put a sticky note on the wall!";
  document.getElementById("app_store_url").href = "https://itunes.apple.com/us/app/walloud/id1363044566?mt=8";
  document.getElementById("app_store_url").style = "display:inline-block;overflow:hidden;background:url(https://linkmaker.itunes.apple.com/assets/shared/badges/en-us/appstore-lrg.svg) no-repeat;width:135px;height:40px;background-size:contain;";
}
