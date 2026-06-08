export function postsQuery({ host, first = 12 }) {
    return `
        query Publication {
            publication(host: "${host}") {
                posts(first: ${first}) {
                    edges {
                        node {
                            title
                            brief
                            url
                            slug
                            id
                            readTimeInMinutes
                            publishedAt
                        }
                    }
                }
            }
        }
    `;
}

export function postBySlugQuery({ host, slug }) {
    return `
        query Publication {
            publication(host: "${host}") {
                post(slug: "${slug}") {
                    id
                    title
                    brief
                    slug
                    readTimeInMinutes
                    publishedAt
                    coverImage { url }
                    content { html }
                }
            }
        }
    `;
}
