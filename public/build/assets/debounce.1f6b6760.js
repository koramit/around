import{A,B as O,C}from"./app.de895529.js";var B=A,L=function(){return B.Date.now()},M=L,j=/\s/;function R(e){for(var r=e.length;r--&&j.test(e.charAt(r)););return r}var F=R,D=F,H=/^\s+/;function P(e){return e&&e.slice(0,D(e)+1).replace(H,"")}var U=P,X=U,_=O,q=C,p=0/0,w=/^[-+]0x[0-9a-f]+$/i,z=/^0b[01]+$/i,G=/^0o[0-7]+$/i,J=parseInt;function K(e){if(typeof e=="number")return e;if(q(e))return p;if(_(e)){var r=typeof e.valueOf=="function"?e.valueOf():e;e=_(r)?r+"":r}if(typeof e!="string")return e===0?e:+e;e=X(e);var t=z.test(e);return t||G.test(e)?J(e.slice(2),t?2:8):w.test(e)?p:+e}var Q=K,V=O,x=M,$=Q,Y="Expected a function",Z=Math.max,ee=Math.min;function ne(e,r,t){var u,s,l,o,i,f,d=0,I=!1,c=!1,b=!0;if(typeof e!="function")throw new TypeError(Y);r=$(r)||0,V(t)&&(I=!!t.leading,c="maxWait"in t,l=c?Z($(t.maxWait)||0,r):l,b="trailing"in t?!!t.trailing:b);function T(n){var a=u,m=s;return u=s=void 0,d=n,o=e.apply(m,a),o}function S(n){return d=n,i=setTimeout(v,r),I?T(n):o}function k(n){var a=n-f,m=n-d,E=r-a;return c?ee(E,l-m):E}function h(n){var a=n-f,m=n-d;return f===void 0||a>=r||a<0||c&&m>=l}function v(){var n=x();if(h(n))return y(n);i=setTimeout(v,k(n))}function y(n){return i=void 0,b&&u?T(n):(u=s=void 0,o)}function N(){i!==void 0&&clearTimeout(i),d=0,u=f=s=i=void 0}function W(){return i===void 0?o:y(x())}function g(){var n=x(),a=h(n);if(u=arguments,s=this,f=n,a){if(i===void 0)return S(f);if(c)return clearTimeout(i),i=setTimeout(v,r),T(f)}return i===void 0&&(i=setTimeout(v,r)),o}return g.cancel=N,g.flush=W,g}var ie=ne;export{ie as d};
