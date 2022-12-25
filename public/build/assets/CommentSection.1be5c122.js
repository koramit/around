import{_ as R,o as n,c as d,b as c,s as g,t as C,f as w,n as j,v as k,r as f,E as A,af as h,ag as E,a as m,w as y,e as T,T as V,A as U,F as I,d as L,B as q,i as S,ah as z,G as D}from"./app.b5967b3a.js";import{_ as B}from"./FormTextarea.a301258d.js";import{_ as M}from"./SpinnerButton.a0473d92.js";import{_ as O}from"./FormCheckbox.06a36834.js";import{I as F}from"./IconEyes.3a2f93e3.js";const G={},Y={viewBox:"0 0 640 512"},J=c("path",{fill:"currentColor",d:"M634 471L36 3.51A16 16 0 0 0 13.51 6l-10 12.49A16 16 0 0 0 6 41l598 467.49a16 16 0 0 0 22.49-2.49l10-12.49A16 16 0 0 0 634 471zM296.79 146.47l134.79 105.38C429.36 191.91 380.48 144 320 144a112.26 112.26 0 0 0-23.21 2.47zm46.42 219.07L208.42 260.16C210.65 320.09 259.53 368 320 368a113 113 0 0 0 23.21-2.46zM320 112c98.65 0 189.09 55 237.93 144a285.53 285.53 0 0 1-44 60.2l37.74 29.5a333.7 333.7 0 0 0 52.9-75.11 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64c-36.7 0-71.71 7-104.63 18.81l46.41 36.29c18.94-4.3 38.34-7.1 58.22-7.1zm0 288c-98.65 0-189.08-55-237.93-144a285.47 285.47 0 0 1 44.05-60.19l-37.74-29.5a333.6 333.6 0 0 0-52.89 75.1 32.35 32.35 0 0 0 0 29.19C89.72 376.41 197.08 448 320 448c36.7 0 71.71-7.05 104.63-18.81l-46.41-36.28C359.28 397.2 339.89 400 320 400z"},null,-1),K=[J];function Q(s,t){return n(),d("svg",Y,K)}var W=R(G,[["render",Q]]);const X={},Z={viewBox:"0 0 512 512"},ee=c("path",{fill:"currentColor",d:"M8.31 189.9l176-151.1c15.41-13.3 39.69-2.509 39.69 18.16v80.05C384.6 137.9 512 170.1 512 322.3c0 61.44-39.59 122.3-83.34 154.1c-13.66 9.938-33.09-2.531-28.06-18.62c45.34-145-21.5-183.5-176.6-185.8v87.92c0 20.7-24.31 31.45-39.69 18.16l-176-151.1C-2.753 216.6-2.784 199.4 8.31 189.9z"},null,-1),te=[ee];function ne(s,t){return n(),d("svg",Z,te)}var oe=R(X,[["render",ne]]);const le={},se={viewBox:"0 0 512 512"},ae=c("path",{fill:"currentColor",d:"M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm101.8-262.2L295.6 256l62.2 62.2c4.7 4.7 4.7 12.3 0 17l-22.6 22.6c-4.7 4.7-12.3 4.7-17 0L256 295.6l-62.2 62.2c-4.7 4.7-12.3 4.7-17 0l-22.6-22.6c-4.7-4.7-4.7-12.3 0-17l62.2-62.2-62.2-62.2c-4.7-4.7-4.7-12.3 0-17l22.6-22.6c4.7-4.7 12.3-4.7 17 0l62.2 62.2 62.2-62.2c4.7-4.7 12.3-4.7 17 0l22.6 22.6c4.7 4.7 4.7 12.3 0 17z"},null,-1),ie=[ae];function ce(s,t){return n(),d("svg",se,ie)}var P=R(le,[["render",ce]]);const re=["innerHTML"],me={class:"mt-4 md:mt-8 flex justify-between text-xs"},de=c("span",null,"reply",-1),ue={class:"italic text-complement"},pe={class:"ml-1 text-accent"},H={__name:"CommentBody",props:{comment:{type:Object,required:!0},showReplies:{type:Boolean},replying:{type:Boolean}},emits:["reply","show-replies"],setup(s){return(t,r)=>(n(),d("div",{class:j(["p-2 pb-1 md:p-4 md:pb-2 bg-primary-darker text-complement rounded",{"rounded-b rounded-t-none":s.comment.parent_body!==void 0&&s.comment.parent_body}])},[c("p",{innerHTML:s.comment.body},null,8,re),c("div",me,[c("div",null,[c("button",{class:"inline-flex items-center text-accent space-x-1 md:space-x-2",onClick:r[0]||(r[0]=e=>t.$emit("reply"))},[s.replying?(n(),g(P,{key:0,class:"w-3 h-3"})):(n(),g(oe,{key:1,class:"w-3 h-3"})),de]),s.comment.replies_count?(n(),d("button",{key:0,class:"inline-flex items-center text-accent ml-2 md:ml-4 space-x-1 md:space-x-2",onClick:r[1]||(r[1]=e=>t.$emit("show-replies"))},[s.showReplies?(n(),g(W,{key:0,class:"h-3 w-3"})):(n(),g(F,{key:1,class:"h-3 w-3"})),c("span",null,C(s.comment.replies_count)+" "+C(s.comment.replies_count>1?"replies":"reply"),1)])):w("",!0)]),c("div",null,[c("span",ue,C(s.comment.at)+" by",1),c("span",pe,C(s.comment.commentator),1)])])],2))}},_e={class:"mt-2 md:mt-4"},fe={key:0,class:"ml-4 mt-4"},ye={class:"border-accent border-l"},ve={__name:"CommentRecursive",props:{propComment:{type:Object,required:!0}},setup(s){const r=k({...s.propComment}),e=f(null),o=f(!1),x=()=>{o.value=!o.value,o.value&&S(()=>{setTimeout(()=>{h().props.value.event.payload=r.id,h().props.value.event.name="comment-recursive-reply-active",h().props.value.event.fire=+new Date},300),e.value.focus()})};A(()=>h().props.value.event.fire,p=>{!p||h().props.value.event.name==="comment-recursive-reply-active"&&o.value&&r.id!==h().props.value.event.payload&&(o.value=!1)});const l=k({body:null,notify_op:!1}),v=()=>{window.axios.post(r.routes.reply,l).then(p=>{l.body=null,l.notify_op=!1,_.value=[...p.data],r.replies_count=_.value.length,S(()=>{o.value=!1,u.value||(u.value=!0)})}).catch(p=>console.log(p))},u=f(!1),_=f([]),$=()=>{if(u.value){u.value=!1;return}if(_.value.length){u.value=!0;return}window.axios.get(r.routes.show).then(p=>{_.value=[...p.data],u.value=!0}).catch(p=>console.log(p))};return(p,i)=>{const b=E("CommentRecursive",!0);return n(),d("li",_e,[m(H,{comment:r,"show-replies":u.value,replying:o.value,onShowReplies:$,onReply:x},null,8,["comment","show-replies","replying"]),m(V,{name:"slide-fade"},{default:y(()=>[o.value?(n(),d("div",fe,[m(B,{name:"body",modelValue:l.body,"onUpdate:modelValue":i[0]||(i[0]=a=>l.body=a),ref_key:"replyInput",ref:e},null,8,["modelValue"]),m(O,{toggler:!0,label:"Notify OP",modelValue:l.notify_op,"onUpdate:modelValue":i[1]||(i[1]=a=>l.notify_op=a),class:"my-2 md:mt-4"},null,8,["modelValue"]),m(M,{spin:l.processing,class:"btn btn-complement mt-4 w-full",onClick:v,disabled:!l.body},{default:y(()=>[T(" REPLY ")]),_:1},8,["spin","disabled"])])):w("",!0)]),_:1}),m(V,{name:"slide-fade"},{default:y(()=>[U(c("ul",ye,[(n(!0),d(I,null,L(_.value,a=>(n(),g(b,{key:a.id,"prop-comment":a,class:"ml-4",onShowReplies:$},null,8,["prop-comment"]))),128))],512),[[q,_.value.length&&u.value]])]),_:1})])}}},be={class:"mb-4 md:mb-8"},ge={__name:"CommentReplyOriented",props:{configs:{type:Object,required:!0}},setup(s){const t=s,r=f(null),e=f([]);window.axios.get(t.configs.routes.reply_index,{params:{commentable_type:t.configs.commentable_type,commentable_id:t.configs.commentable_id}}).then(l=>e.value=l.data).catch(l=>console.log(l));const o=k({commentable_type:t.configs.commentable_type,commentable_id:t.configs.commentable_id,body:null,notify_op:!1,processing:!1}),x=()=>{o.processing=!0,window.axios.post(t.configs.routes.reply_store,o).then(l=>{e.value.push(l.data),o.body=null,o.notify_op=!1}).catch(l=>console.log(l)).finally(()=>o.processing=!1)};return(l,v)=>(n(),d("div",null,[c("ul",be,[m(z,{name:"flip-list"},{default:y(()=>[(n(!0),d(I,null,L(e.value,u=>(n(),g(ve,{key:u.id,"prop-comment":u},null,8,["prop-comment"]))),128))]),_:1})]),m(B,{name:"body",modelValue:o.body,"onUpdate:modelValue":v[0]||(v[0]=u=>o.body=u),ref_key:"commentInput",ref:r},null,8,["modelValue"]),m(O,{toggler:!0,label:"Notify OP",modelValue:o.notify_op,"onUpdate:modelValue":v[1]||(v[1]=u=>o.notify_op=u),class:"my-2 md:mt-4"},null,8,["modelValue"]),m(M,{spin:o.processing,class:"btn btn-accent mt-4 w-full",onClick:x,disabled:!o.body},{default:y(()=>[T(" POST ")]),_:1},8,["spin","disabled"])]))}},he={class:"mb-4 md:mb-8"},xe=["id"],we=["onClick"],$e=["innerHTML"],Ce={key:0,class:"flex justify-between items-start space-x-2 md:space-x-4 p-2 md:p-4 rounded-t-lg bg-complement/50 text-white"},ke=["innerHTML"],Ve={__name:"CommentTimelineOriented",props:{configs:{type:Object,required:!0}},setup(s){const t=s,r=f([]);window.axios.get(t.configs.routes.timeline_index,{params:{commentable_type:t.configs.commentable_type,commentable_id:t.configs.commentable_id}}).then(i=>r.value=i.data).catch(i=>console.log(i));const e=k({commentable_type:t.configs.commentable_type,commentable_id:t.configs.commentable_id,body:null,notify_op:!1,parent_id:null,processing:!1}),o=f(null),x=()=>{e.processing=!0,window.axios.post(t.configs.routes.timeline_store,e).then(i=>{r.value.push(i.data),e.body=null,e.notify_op=!1,e.parent_id=null,l.body=null}).catch(i=>console.log(i)).finally(()=>e.processing=!1)},l=k({body:null}),v=i=>{if(e.parent_id===i.id){l.body=null,e.parent_id=null,e.body=null;return}l.body=i.body,e.parent_id=i.id,p("#comment_body"),setTimeout(()=>o.value.focus(),300)},u=()=>{l.body=null,e.parent_id=null},_=f(null),$=i=>{_.value=i,p(`#comment-id-${i}`),setTimeout(()=>_.value=null,2e3)},{smoothScroll:p}=D();return(i,b)=>(n(),d("div",null,[c("ul",he,[m(z,{name:"flip-list"},{default:y(()=>[(n(!0),d(I,null,L(r.value,a=>(n(),d("li",{class:j(["mt-2 md:mt-4 scroll-mt-28 md:scroll-mt-14",{"animate-bounce":_.value===a.id}]),key:a.id,id:`comment-id-${a.id}`},[a.parent_body?(n(),d("div",{key:0,class:"cursor-pointer",onClick:N=>$(a.parent_id)},[c("p",{class:"px-4 py-1 md:px-8 md:py-2 text-xs md:text-sm bg-complement/50 text-white rounded-t italic",innerHTML:a.parent_body},null,8,$e)],8,we)):w("",!0),m(H,{onReply:N=>v(a),replying:a.id===e.parent_id,comment:a},null,8,["onReply","replying","comment"])],10,xe))),128))]),_:1})]),m(V,{name:"slide-fade"},{default:y(()=>[l.body?(n(),d("div",Ce,[c("p",{innerHTML:l.body,class:"cursor-pointer",onClick:b[0]||(b[0]=a=>$(e.parent_id))},null,8,ke),c("button",{onClick:u},[m(P,{class:"w-4 h-4"})])])):w("",!0)]),_:1}),m(B,{name:"comment_body",modelValue:e.body,"onUpdate:modelValue":b[1]||(b[1]=a=>e.body=a),ref_key:"commentInput",ref:o},null,8,["modelValue"]),m(O,{toggler:!0,label:"Notify OP",modelValue:e.notify_op,"onUpdate:modelValue":b[2]||(b[2]=a=>e.notify_op=a),class:"my-2 md:mt-4"},null,8,["modelValue"]),m(M,{spin:e.processing,class:"btn btn-accent mt-4 w-full",onClick:x,disabled:!e.body},{default:y(()=>[T(C(e.parent_id?"REPLY":"POST"),1)]),_:1},8,["spin","disabled"])]))}},Re={name:"IconAdjustments"},Te={xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor","stroke-width":"2"},Ie=c("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"},null,-1),Le=[Ie];function Be(s,t,r,e,o,x){return n(),d("svg",Te,Le)}var Me=R(Re,[["render",Be]]);const Oe={class:"text-xs md:text-sm text-accent"},Ne={__name:"CommentSection",props:{configs:{type:Object,required:!0}},setup(s){const t=f(h().props.value.user.preferences.discussion_mode);return(r,e)=>(n(),d("div",null,[c("div",Oe,[m(Me,{class:"mr-1 w-3 h-4 inline"}),t.value==="reply"?(n(),d("button",{key:0,onClick:e[0]||(e[0]=o=>t.value="timeline")}," Timeline oriented ")):t.value==="timeline"?(n(),d("button",{key:1,onClick:e[1]||(e[1]=o=>t.value="reply")}," Reply oriented ")):w("",!0)]),m(V,{name:"slide-fade",mode:"in-out"},{default:y(()=>[t.value==="reply"?(n(),g(ge,{key:0,configs:s.configs},null,8,["configs"])):t.value==="timeline"?(n(),g(Ve,{key:1,configs:s.configs},null,8,["configs"])):w("",!0)]),_:1})]))}};export{W as I,Ne as _};