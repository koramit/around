import{x as A,y as O,z as C}from"./app.7955817d.js";var L=A,M=function(){return L.Date.now()},j=M,B=/\s/;function R(e){for(var r=e.length;r--&&B.test(e.charAt(r)););return r}var F=R,z=F,D=/^\s+/;function H(e){return e&&e.slice(0,z(e)+1).replace(D,"")}var P=H,U=P,_=O,X=C,p=0/0,q=/^[-+]0x[0-9a-f]+$/i,w=/^0b[01]+$/i,G=/^0o[0-7]+$/i,J=parseInt;function K(e){if(typeof e=="number")return e;if(X(e))return p;if(_(e)){var r=typeof e.valueOf=="function"?e.valueOf():e;e=_(r)?r+"":r}if(typeof e!="string")return e===0?e:+e;e=U(e);var t=w.test(e);return t||G.test(e)?J(e.slice(2),t?2:8):q.test(e)?p:+e}var Q=K,V=O,g=j,$=Q,Y="Expected a function",Z=Math.max,ee=Math.min;function ne(e,r,t){var u,s,l,o,i,f,d=0,I=!1,c=!1,b=!0;if(typeof e!="function")throw new TypeError(Y);r=$(r)||0,V(t)&&(I=!!t.leading,c="maxWait"in t,l=c?Z($(t.maxWait)||0,r):l,b="trailing"in t?!!t.trailing:b);function x(n){var a=u,m=s;return u=s=void 0,d=n,o=e.apply(m,a),o}function S(n){return d=n,i=setTimeout(v,r),I?x(n):o}function k(n){var a=n-f,m=n-d,E=r-a;return c?ee(E,l-m):E}function h(n){var a=n-f,m=n-d;return f===void 0||a>=r||a<0||c&&m>=l}function v(){var n=g();if(h(n))return y(n);i=setTimeout(v,k(n))}function y(n){return i=void 0,b&&u?x(n):(u=s=void 0,o)}function N(){i!==void 0&&clearTimeout(i),d=0,u=f=s=i=void 0}function W(){return i===void 0?o:y(g())}function T(){var n=g(),a=h(n);if(u=arguments,s=this,f=n,a){if(i===void 0)return S(f);if(c)return clearTimeout(i),i=setTimeout(v,r),x(f)}return i===void 0&&(i=setTimeout(v,r)),o}return T.cancel=N,T.flush=W,T}var ie=ne;export{ie as d};