import{_ as R,b as o,c as d,d as r,k as g,t as k,h as w,s as P,l as C,r as f,z,Q as h,N as U,e as m,f as y,g as T,q as V,G as E,F as I,m as L,H as D,n as B,P as M,J as F}from"./app-CC1FPugz.js";import{_ as O}from"./FormTextarea-Dvb3ffAu.js";import{_ as S}from"./SpinnerButton-DXbXdbzm.js";import{_ as j}from"./FormCheckbox-BnRXqYhA.js";import{I as A}from"./IconEyes-DKAigjVq.js";import{I as G}from"./IconEyesSlash-bYYOAXuo.js";const Y={},J={viewBox:"0 0 512 512"},Q=r("path",{fill:"currentColor",d:"M8.31 189.9l176-151.1c15.41-13.3 39.69-2.509 39.69 18.16v80.05C384.6 137.9 512 170.1 512 322.3c0 61.44-39.59 122.3-83.34 154.1c-13.66 9.938-33.09-2.531-28.06-18.62c45.34-145-21.5-183.5-176.6-185.8v87.92c0 20.7-24.31 31.45-39.69 18.16l-176-151.1C-2.753 216.6-2.784 199.4 8.31 189.9z"},null,-1),K=[Q];function W(s,t){return o(),d("svg",J,K)}const X=R(Y,[["render",W]]),Z={},ee={viewBox:"0 0 512 512"},te=r("path",{fill:"currentColor",d:"M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm101.8-262.2L295.6 256l62.2 62.2c4.7 4.7 4.7 12.3 0 17l-22.6 22.6c-4.7 4.7-12.3 4.7-17 0L256 295.6l-62.2 62.2c-4.7 4.7-12.3 4.7-17 0l-22.6-22.6c-4.7-4.7-4.7-12.3 0-17l62.2-62.2-62.2-62.2c-4.7-4.7-4.7-12.3 0-17l22.6-22.6c4.7-4.7 12.3-4.7 17 0l62.2 62.2 62.2-62.2c4.7-4.7 12.3-4.7 17 0l22.6 22.6c4.7 4.7 4.7 12.3 0 17z"},null,-1),ne=[te];function oe(s,t){return o(),d("svg",ee,ne)}const H=R(Z,[["render",oe]]),le=["innerHTML"],se={class:"mt-4 md:mt-8 flex justify-between text-xs"},ie=r("span",null,"reply",-1),ae={class:"italic text-complement"},ce={class:"ml-1 text-accent"},N={__name:"CommentBody",props:{comment:{type:Object,required:!0},showReplies:{type:Boolean},replying:{type:Boolean}},emits:["reply","show-replies"],setup(s){return(t,c)=>(o(),d("div",{class:P(["p-2 pb-1 md:p-4 md:pb-2 bg-primary-darker text-complement rounded",{"rounded-b rounded-t-none":s.comment.parent_body!==void 0&&s.comment.parent_body}])},[r("p",{innerHTML:s.comment.body},null,8,le),r("div",se,[r("div",null,[r("button",{class:"inline-flex items-center text-accent space-x-1 md:space-x-2",onClick:c[0]||(c[0]=e=>t.$emit("reply"))},[s.replying?(o(),g(H,{key:0,class:"w-3 h-3"})):(o(),g(X,{key:1,class:"w-3 h-3"})),ie]),s.comment.replies_count?(o(),d("button",{key:0,class:"inline-flex items-center text-accent ml-2 md:ml-4 space-x-1 md:space-x-2",onClick:c[1]||(c[1]=e=>t.$emit("show-replies"))},[s.showReplies?(o(),g(G,{key:0,class:"h-3 w-3"})):(o(),g(A,{key:1,class:"h-3 w-3"})),r("span",null,k(s.comment.replies_count)+" "+k(s.comment.replies_count>1?"replies":"reply"),1)])):w("",!0)]),r("div",null,[r("span",ae,k(s.comment.at)+" by",1),r("span",ce,k(s.comment.commentator),1)])])],2))}},re={class:"mt-2 md:mt-4"},me={key:0,class:"ml-4 mt-4"},de={class:"border-accent border-l"},ue={__name:"CommentRecursive",props:{propComment:{type:Object,required:!0}},setup(s){const c=C({...s.propComment}),e=f(null),n=f(!1),x=()=>{n.value=!n.value,n.value&&B(()=>{setTimeout(()=>{h().props.event.payload=c.id,h().props.event.name="comment-recursive-reply-active",h().props.event.fire=+new Date},300),e.value.focus()})};z(()=>h().props.event.fire,p=>{p&&h().props.event.name==="comment-recursive-reply-active"&&n.value&&c.id!==h().props.event.payload&&(n.value=!1)});const l=C({body:null,notify_op:!1}),b=()=>{window.axios.post(c.routes.reply,l).then(p=>{l.body=null,l.notify_op=!1,_.value=[...p.data],c.replies_count=_.value.length,B(()=>{n.value=!1,u.value||(u.value=!0)})}).catch(p=>console.log(p))},u=f(!1),_=f([]),$=()=>{if(u.value){u.value=!1;return}if(_.value.length){u.value=!0;return}window.axios.get(c.routes.show).then(p=>{_.value=[...p.data],u.value=!0}).catch(p=>console.log(p))};return(p,a)=>{const v=U("CommentRecursive",!0);return o(),d("li",re,[m(N,{comment:c,"show-replies":u.value,replying:n.value,onShowReplies:$,onReply:x},null,8,["comment","show-replies","replying"]),m(V,{name:"slide-fade"},{default:y(()=>[n.value?(o(),d("div",me,[m(O,{name:"body",modelValue:l.body,"onUpdate:modelValue":a[0]||(a[0]=i=>l.body=i),ref_key:"replyInput",ref:e},null,8,["modelValue"]),m(j,{toggler:!0,label:"Notify OP",modelValue:l.notify_op,"onUpdate:modelValue":a[1]||(a[1]=i=>l.notify_op=i),class:"my-2 md:mt-4"},null,8,["modelValue"]),m(S,{spin:l.processing,class:"btn btn-complement mt-4 w-full",onClick:b,disabled:!l.body},{default:y(()=>[T(" REPLY ")]),_:1},8,["spin","disabled"])])):w("",!0)]),_:1}),m(V,{name:"slide-fade"},{default:y(()=>[E(r("ul",de,[(o(!0),d(I,null,L(_.value,i=>(o(),g(v,{key:i.id,"prop-comment":i,class:"ml-4",onShowReplies:$},null,8,["prop-comment"]))),128))],512),[[D,_.value.length&&u.value]])]),_:1})])}}},pe={class:"mb-4 md:mb-8"},_e={__name:"CommentReplyOriented",props:{configs:{type:Object,required:!0}},setup(s){const t=s,c=f(null),e=f([]);window.axios.get(t.configs.routes.reply_index,{params:{commentable_type:t.configs.commentable_type,commentable_id:t.configs.commentable_id}}).then(l=>e.value=l.data).catch(l=>console.log(l));const n=C({commentable_type:t.configs.commentable_type,commentable_id:t.configs.commentable_id,body:null,notify_op:!1,processing:!1}),x=()=>{n.processing=!0,window.axios.post(t.configs.routes.reply_store,n).then(l=>{e.value.push(l.data),n.body=null,n.notify_op=!1}).catch(l=>console.log(l)).finally(()=>n.processing=!1)};return(l,b)=>(o(),d("div",null,[r("ul",pe,[m(M,{name:"flip-list"},{default:y(()=>[(o(!0),d(I,null,L(e.value,u=>(o(),g(ue,{key:u.id,"prop-comment":u},null,8,["prop-comment"]))),128))]),_:1})]),m(O,{name:"body",modelValue:n.body,"onUpdate:modelValue":b[0]||(b[0]=u=>n.body=u),ref_key:"commentInput",ref:c},null,8,["modelValue"]),m(j,{toggler:!0,label:"Notify OP",modelValue:n.notify_op,"onUpdate:modelValue":b[1]||(b[1]=u=>n.notify_op=u),class:"my-2 md:mt-4"},null,8,["modelValue"]),m(S,{spin:n.processing,class:"btn btn-accent mt-4 w-full",onClick:x,disabled:!n.body},{default:y(()=>[T(" POST ")]),_:1},8,["spin","disabled"])]))}},fe={class:"mb-4 md:mb-8"},ye=["id"],be=["onClick"],ve=["innerHTML"],ge={key:0,class:"flex justify-between items-start space-x-2 md:space-x-4 p-2 md:p-4 rounded-t-lg bg-complement/50 text-white"},he=["innerHTML"],xe={__name:"CommentTimelineOriented",props:{configs:{type:Object,required:!0}},setup(s){const t=s,c=f([]);window.axios.get(t.configs.routes.timeline_index,{params:{commentable_type:t.configs.commentable_type,commentable_id:t.configs.commentable_id}}).then(a=>c.value=a.data).catch(a=>console.log(a));const e=C({commentable_type:t.configs.commentable_type,commentable_id:t.configs.commentable_id,body:null,notify_op:!1,parent_id:null,processing:!1}),n=f(null),x=()=>{e.processing=!0,window.axios.post(t.configs.routes.timeline_store,e).then(a=>{c.value.push(a.data),e.body=null,e.notify_op=!1,e.parent_id=null,l.body=null}).catch(a=>console.log(a)).finally(()=>e.processing=!1)},l=C({body:null}),b=a=>{if(e.parent_id===a.id){l.body=null,e.parent_id=null,e.body=null;return}l.body=a.body,e.parent_id=a.id,p("#comment_body"),setTimeout(()=>n.value.focus(),300)},u=()=>{l.body=null,e.parent_id=null},_=f(null),$=a=>{_.value=a,p(`#comment-id-${a}`),setTimeout(()=>_.value=null,2e3)},{smoothScroll:p}=F();return(a,v)=>(o(),d("div",null,[r("ul",fe,[m(M,{name:"flip-list"},{default:y(()=>[(o(!0),d(I,null,L(c.value,i=>(o(),d("li",{class:P(["mt-2 md:mt-4 scroll-mt-28 md:scroll-mt-14",{"animate-bounce":_.value===i.id}]),key:i.id,id:`comment-id-${i.id}`},[i.parent_body?(o(),d("div",{key:0,class:"cursor-pointer",onClick:q=>$(i.parent_id)},[r("p",{class:"px-4 py-1 md:px-8 md:py-2 text-xs md:text-sm bg-complement/50 text-white rounded-t italic",innerHTML:i.parent_body},null,8,ve)],8,be)):w("",!0),m(N,{onReply:q=>b(i),replying:i.id===e.parent_id,comment:i},null,8,["onReply","replying","comment"])],10,ye))),128))]),_:1})]),m(V,{name:"slide-fade"},{default:y(()=>[l.body?(o(),d("div",ge,[r("p",{innerHTML:l.body,class:"cursor-pointer",onClick:v[0]||(v[0]=i=>$(e.parent_id))},null,8,he),r("button",{onClick:u},[m(H,{class:"w-4 h-4"})])])):w("",!0)]),_:1}),m(O,{name:"comment_body",modelValue:e.body,"onUpdate:modelValue":v[1]||(v[1]=i=>e.body=i),ref_key:"commentInput",ref:n},null,8,["modelValue"]),m(j,{toggler:!0,label:"Notify OP",modelValue:e.notify_op,"onUpdate:modelValue":v[2]||(v[2]=i=>e.notify_op=i),class:"my-2 md:mt-4"},null,8,["modelValue"]),m(S,{spin:e.processing,class:"btn btn-accent mt-4 w-full",onClick:x,disabled:!e.body},{default:y(()=>[T(k(e.parent_id?"REPLY":"POST"),1)]),_:1},8,["spin","disabled"])]))}},we={name:"IconAdjustments"},$e={xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor","stroke-width":"2"},ke=r("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"},null,-1),Ce=[ke];function Ve(s,t,c,e,n,x){return o(),d("svg",$e,Ce)}const Re=R(we,[["render",Ve]]),Te={class:"text-xs md:text-sm text-accent"},Pe={__name:"CommentSection",props:{configs:{type:Object,required:!0}},setup(s){const t=f(h().props.user.preferences.discussion_mode);return(c,e)=>(o(),d("div",null,[r("div",Te,[m(Re,{class:"mr-1 w-3 h-4 inline"}),t.value==="reply"?(o(),d("button",{key:0,onClick:e[0]||(e[0]=n=>t.value="timeline")}," Timeline oriented ")):t.value==="timeline"?(o(),d("button",{key:1,onClick:e[1]||(e[1]=n=>t.value="reply")}," Reply oriented ")):w("",!0)]),m(V,{name:"slide-fade",mode:"in-out"},{default:y(()=>[t.value==="reply"?(o(),g(_e,{key:0,configs:s.configs},null,8,["configs"])):t.value==="timeline"?(o(),g(xe,{key:1,configs:s.configs},null,8,["configs"])):w("",!0)]),_:1})]))}};export{Pe as _};
