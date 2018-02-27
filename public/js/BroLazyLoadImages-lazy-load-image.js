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

                var width = item.getAttribute('width');
                var height = item.getAttribute('height');

                if (!src) {
                    return false;
                }

                var img = new Image();

                img.src = src;
                img.size = item.size;
                img.srcset = srcset;
                img.className = item.className + ' progressive animated hide ';
                img.height = item.height;
                img.width = item.width;

                if (img.complete) {
                    addImg(item);
                } else {
                    img.onloadend = addImg(item);
                }


                function addImg(item) {

                    if (item) {
                        var old_img = item.parentNode.getElementsByTagName('img');
                        var p_n = item.parentNode;
                        p_n.replaceChild(img, old_img.item(0));

                        var added_image = p_n.getElementsByTagName('img');
                        added_image.item(0).classList.remove('hide');

                        added_image.item(0).style.minHeight = height + 'px';
                        added_image.item(0).style.minwidth = width + 'px';

                        added_image.item(0).addEventListener('animationend', function (e) {
                            e.target.style.minHeight = '';
                            e.target.style.minwidth = '';

                            e.target.classList.remove('animated');
                        });


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