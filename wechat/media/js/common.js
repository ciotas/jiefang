  var Utils = {

     throttle: function (fn, delay, immediate, debounce) {
            var curr = +new Date(),//褰撳墠浜嬩欢
                last_call = 0,
                last_exec = 0,
                timer = null,
                diff, //鏃堕棿宸�
                context,//涓婁笅鏂�
                args,
                exec = function () {
                    last_exec = curr;
                    fn.apply(context, args);
                };
            return function () {
                curr = +new Date();
                context = this,
                    args = arguments,
                    diff = curr - (debounce ? last_call : last_exec) - delay;
                clearTimeout(timer);
                if (debounce) {
                    if (immediate) {
                        timer = setTimeout(exec, delay);
                    } else if (diff >= 0) {
                        exec();
                    }
                } else {
                    if (diff >= 0) {
                        exec();
                    } else if (immediate) {
                        timer = setTimeout(exec, -diff);
                    }
                }
                last_call = curr;
            }
        }
  }