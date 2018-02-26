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
            }, 300);

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

            function payload() {
                var src = item.getAttribute('data-lazy-src');
                var srcset = item.getAttribute('data-lazy-srcset');

                if (!src) {
                    return false;
                }

                var img = new Image();

                img.src = src;
                img.size = item.size;
                img.srcset = srcset;
                img.className = item.className + ' animated';
                img.height = item.height;
                img.width = item.width;

                if (img.complete) {
                    addImg();
                } else {
                    img.onload = addImg;
                }

                function addImg() {
                    var old_img = item.parentNode.getElementsByTagName('img');
                    var p_n = item.parentNode;
                    p_n.replaceChild(img, old_img.item(0));
                    var added_image = p_n.getElementsByTagName('img');

                    if (window.requestAnimationFrame) {
                        added_image.item(0).addEventListener('animationend', function (e) {
                            e.target.classList.remove('animated');
                        });
                    } else {
                        added_image.item(0).classList.remove('animated');
                    }
                }
            }

            if (window.requestAnimationFrame) {
                requestAnimationFrame(function () {
                    payload(item);
                });

            }

        }

    }, false);
}