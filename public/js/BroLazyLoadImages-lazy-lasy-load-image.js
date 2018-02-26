if (window.addEventListener && window.requestAnimationFrame && document.getElementsByClassName) {
    window.addEventListener('load', function () {

        // start
        var pItem = document.querySelectorAll('[data-lazy-src]');
        var pCount, timer;

        // scroll and resize events
        window.addEventListener('scroll', scroller, false);
        window.addEventListener('resize', scroller, false);


        if (MutationObserver) {
            var observer = new MutationObserver(function () {
                if (pItem.length !== pCount) {
                    inView();
                }
            });
            observer.observe(document.body, {subtree: true, childList: true, attributes: true, characterData: true});
        }

        // initial check
        inView();


        // throttled scroll/resize
        function scroller() {

            timer = timer || setTimeout(function () {
                timer = null;
                inView();
            }, 300);

        }


        // image in view?
        function inView() {

            if (pItem.length) {

                var wT = window.pageYOffset, wB = wT + window.innerHeight, cRect, pT, pB;
                var p = 0;

                while (p < pItem.length) {

                    cRect = pItem[p].getBoundingClientRect();
                    pT = wT + cRect.top;
                    pB = pT + cRect.height;

                    if (wT < pB && wB > pT) {
                        loadFullImage(pItem[p]);
                        // pItem[p].classList.remove('replace');
                    }
                    p++;
                }

                pCount = pItem.length;

            }

        }


        // replace with full image
        function loadFullImage(item) {

            var src = item.getAttribute('data-lazy-src');
            var srcset = item.getAttribute('data-lazy-srcset');

            if (!src) {
                return false;
            }

            // // load image
            var img = new Image();


            img.src = src;
            img.size = item.size;
            img.srcset = srcset;
            img.className = item.className;
            img.height = item.height;
            img.width = item.width;

            if (img.complete) {
                addImg();
            } else {
                img.onload = addImg;
            }

            // replace image
            function addImg() {


                requestAnimationFrame(function () {

                    var old_img = item.parentNode.getElementsByClassName('wp-post-image');

                    item.parentNode.replaceChild(img, old_img[0]).addEventListener(
                        'animationend',
                        function (e) {
                            item.getElementsByClassName('img').classList.remove('blur');
                        }
                    );

                });

            }

        }


    }, false);
}