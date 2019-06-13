// if (window.addEventListener && window.requestAnimationFrame && document.getElementsByClassName) {
//
//   window.addEventListener('load', function () {
//
//     // start
//     var pItem = document.getElementsByClassName('progressive replace')
//     var pCount, timer
//
//     // scroll and resize events
//     window.addEventListener('scroll', scroller, false)
//     window.addEventListener('resize', scroller, false)
//
//     // DOM mutation observer
//     if (MutationObserver) {
//
//       var observer = new MutationObserver(function () {
//         if (pItem.length !== pCount) {
//           inView()
//         }
//       })
//       observer.observe(document.body, {
//         subtree: true,
//         childList: true,
//         attributes: true,
//         characterData: true,
//       })
//
//     }
//
//     // initial check
//     inView()
//
//     // throttled scroll/resize
//     function scroller () {
//
//       timer = timer || setTimeout(function () {
//         timer = null
//         inView()
//       }, 100)
//
//     }
//
//     // image in view?
//     function inView () {
//
//       if (pItem.length) {
//         requestAnimationFrame(function () {
//
//           var wH = window.innerHeight, cRect, cT, cH, p = 0
//           while (p < pItem.length) {
//
//             cRect = pItem[p].getBoundingClientRect()
//             cT = cRect.top
//             cH = cRect.height
//
//             if (0 < cT + cH && wH > cT) {
//               loadFullImage(pItem[p])
//             } else {
//               p++
//             }
//
//           }
//           pCount = pItem.length
//
//         })
//       }
//
//     }
//
//     // replace with full image
//     function loadFullImage (item) {
//
//       var attributes = decode(item.getAttribute('data-attributes'))
//
//       var img = new Image()
//
//       for (var key in attributes) {
//
//         if ('class' === key) {
//           img.className = attributes[key] + ' reveal '
//         } else {
//           img[key] = attributes[key]
//         }
//
//       }
//
//       if (img.complete) {
//         addImg()
//       } else {
//         img.onload = addImg
//       }
//
//       function decode (data) {
//         var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/='
//         var o1, o2, o3, h1, h2, h3, h4, bits, i = 0, enc = ''
//         do {
//           h1 = b64.indexOf(data.charAt(i++))
//           h2 = b64.indexOf(data.charAt(i++))
//           h3 = b64.indexOf(data.charAt(i++))
//           h4 = b64.indexOf(data.charAt(i++))
//           bits = h1 << 18 | h2 << 12 | h3 << 6 | h4
//           o1 = bits >> 16 & 0xff
//           o2 = bits >> 8 & 0xff
//           o3 = bits & 0xff
//           if (h3 === 64) enc += String.fromCharCode(o1)
//           else if (h4 === 64) enc += String.fromCharCode(o1, o2)
//           else enc += String.fromCharCode(o1, o2, o3)
//         } while (i < data.length)
//         return JSON.parse(enc)
//       }
//
//       // replace image
//       function addImg () {
//
//         requestAnimationFrame(function () {
//
//           /* preview image */
//           var pImg = item.querySelector && item.querySelector('img.preview')
//
//           /* add full image */
//           item.insertBefore(img, pImg && pImg.nextSibling).addEventListener('animationend', function () {
//
//             // remove preview image
//             if (pImg) {
//               item.removeChild(pImg)
//             }
//             img.classList.remove('reveal')
//
//           })
//
//         })
//
//       }
//
//       item.classList.remove('replace')
//
//     }
//
//   }, false)
//
// }
