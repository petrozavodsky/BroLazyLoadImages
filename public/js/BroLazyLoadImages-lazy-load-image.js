if (window.addEventListener && document.getElementsByClassName) {
    window.addEventListener('load', function () {

        var pItem = document.querySelectorAll('[data-lazy-src]');
        var pCount, timer;

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

        inView();

        function scroller() {

            timer = timer || setTimeout(function () {
                timer = null;
                inView();
            }, 400);

        }

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
                    }
                    p++;
                }
                pCount = pItem.length;
            }

        }

        function loadFullImage(item) {

            function payload(item) {
                var src = item.getAttribute('data-lazy-src');
                var srcset = item.getAttribute('data-lazy-srcset');

                if (item.hasAttribute('data-lazy-src')) {

                    var img = new Image();

                    img.src = src;
                    img.srcset = srcset;
                    img.height = item.height;
                    img.width = item.width;

                    if (img.complete) {
                        addImg(item);
                    } else {
                        img.onloadend = addImg(item);
                    }

                    function addImg(item) {

                        if (item) {

                            item.setAttribute('src', src);
                            item.classList.add('animated');

                            item.addEventListener('animationend', function (e) {
                                item.setAttribute('srcset', srcset);
                                e.target.removeAttribute('data-lazy-srcset');
                                e.target.classList.remove('animated');
                                e.target.removeAttribute('data-lazy-src');
                            });

                        }
                    }

                }
            }

            if (window.requestAnimationFrame) {

                requestAnimationFrame(function () {
                    payload(item);
                });

            } else {
                payload(item);
            }

        }

    }, false);
}